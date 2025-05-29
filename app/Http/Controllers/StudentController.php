<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use App\Models\FormStructure;
use TCPDF;
use TCPDF_FONTS;
use Exception;
use setasign\Fpdi\Tcpdf\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use MongoDB\BSON\Regex;
use App\Http\Requests\EditStudentRequest;
use App\CustomLibrary\JWSExtractor;

class StudentController extends Controller
{
    public function createStudent(Request $request){

        $structure = FormStructure::where('form_id', 1)->first();
        if($request->isMethod("POST"))
        {
            $data = $request->all();            
            unset($data["_token"]);
            try{
                $student = new Student();
                foreach($data as $key => $value){
                    if($key == "_token"){
                        continue;
                    }
                    $student->{$key} = $value;
                }
                if(count($data) > 0){
                    $student->save();
                }
                $this->updateFormStructure($structure, array_keys($data));
            }
            catch(Exception $e){
                return response()->json([
                    'message' => 'An error has occured while saving student record. '.$e->getMessage(),
                    'error' => $e,
                ], 403);
            }
            return response()->json([
                'message' => 'Student record saved successfully'
            ]);
        }
        
        return view('student.createStudent',[
            'structure' => $structure
        ]);
    }

    private function updateFormStructure($structure, $fields = []){
        if(count($fields) == 0){
            return;
        }
        $new_fields = [];
        foreach($fields as $field){
            if(!in_array($field, $structure->fields)){
                $new_fields[] = $field;
            }
        }
        $structure->fields = array_merge($structure->fields, $new_fields);
        $structure->save();
    }

    //method to get student record
    public function displayStudents(Request $request, $status="")
    {
        $query = $request->get('search');
        $page = $request->get('page', 1);

        /**
         * Value of $status can be either signed or pending
         * If $ststus == "", we are gonna display all the students in the list
         */

        $sql = Student::when($query, function ($queryBuilder) use ($query, $status) {
            $regex = new Regex($query, 'i'); // Case-insensitive regex search
            return $queryBuilder->where(function ($q) use ($regex) {
                $q->orWhere('Name_of_Students', $regex)
                    ->orWhere('छात्रों_का_नाम', $regex)
                    ->orWhere('Registration_No', $regex)
                    ->orWhere('Department', $regex)
                    ->orWhere('विभाग', $regex)
                    ->orWhere('Course', $regex)
                    ->orWhere('पाठ्यक्रम', $regex)
                    ->orWhere('Batch', $regex)
                    ->orWhere('Year', $regex)
                    ->orWhere('Sports', $regex);

            });
        });
        
        if($status == "signed"){
            $sql->where('approved', 1);
        }
        elseif($status== "pending"){
            $sql->where('approved', 0);
        }
        
        
        $students = $sql->orderBy('Sl_No', 'asc')->paginate(10);

        return view('student.displayStudents', compact('students'))->with([
            'page' => $page,
            'search' => $query,
            'status' => $status
        ]);
    }

    //method to view a student record
    public function viewStudent(Request $request, $id){
        $student = Student::find($id);
        if(!$student){
            return redirect()->route('displayStudents')->with([
                'message' => "Student record with id = '{$id}' is not found",
                'status' => 'warning'
            ]);
        }

        $isSigned = $student->isSigned();
        $isSignatureValid = $student->isSignatureValid();
        $isDataIntegrityVerified = $student->verifySignedData();
        $signerName = $student->getSignerName();
        $jws = $student->getJWS();
        $payload = $jws==null?[]:JWSExtractor::decodePayload($jws);
        $actualData = $student->toArray();
        unset($actualData['approved_by']); // Exclude the 'approved_by' from the data to verify
        unset($actualData['updated_at']); // Exclude 'updated_at' if it exists, as it may change over time
        //unset($actualData['approved']); // Exclude 'updated_at' if it exists, as it may change over time
        /* 
        dd([
            'student' => $student,
            'isEsigned' => $isSigned,
            'isSignatureValid' => $isSignatureValid,
            'isDataIntegrityVerified' => $isDataIntegrityVerified,
            'signerName' => $signerName,
            'payload' => $payload,
            'actualData' => $actualData,
        ]); */


        $page = $request->get('page', 1);
        $status = $request->get('status', "");
        return view('student.viewStudent',compact('student', 
        'isSigned', 
        'isSignatureValid', 
        'isDataIntegrityVerified',
        'signerName','jws'))->with([
            'page' => $page,
            'status' => $status
        ]);
    }

    //method to show certificate of a student
    public function showCertificate(Request $request, $studentId, $type = ''){
        $student = Student::find($studentId);
        $data = [
            'student' => $student
        ];

        if(!isset($student->approved) || $student->approved == 0){
            $data = array_merge($data, [
                'message' => 'Student record is not approved yet.',
                'status' => 'warning'
            ]);
        }
        else{
            $data = array_merge($data, [
                'message' => 'Student record is approved.',
                'status' => 'success'
            ]);
        }
        
        // ini_set('max_execution_time', 300);
        if($type == 'pdf'){
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                //'default_font' => 'NotoSansDevanagari', // Change this to your font name
                'autoScriptToLang' => true,  // Automatically detects Hindi script
                'autoLangToFont' => true,  // Ensures correct font is applied
            ]);
    
            // Load Hindi content
            $html = View::make('student.template.certificateDoc', $data)->render();
            $mpdf->WriteHTML($html);
    
            return response()->streamDownload(function () use ($mpdf) {
                echo $mpdf->Output('', 'S');
            }, 'NSUCertificate.pdf');

            // $pdf = Pdf::loadView('student.template.certificateDoc', $data);
            // return $pdf->download('NSUCertificate.pdf');
        }
        return view('student.template.certificate', $data);
    }

    //method to approve a student record
    public function approveStudent(Request $request){
        $user = auth()->user();
        //Only admin can approve student record
        if($user->isRole(['admin']) == false){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'You are not authorized to approve student record'
                ], 403);
            }
            return redirect()->route('displayStudents')->with([
                'message' => 'You are not authorized to approve student record',
                'status' => 'error'
            ]);
        }

        // Validation
        $request->validate([
            'studentId' => 'required|exists:students,_id',
            'jws' => 'required|string'
        ]);

        try{
            $studentId = $request->post('studentId');
            $jws = $request->post('jws'); //Signed Student Information

            $student = Student::find($studentId);            
            
            $student->approved = 1;
            $approved_by = [
                'approver_id' => auth()->user()->_id,
                'approved_at' => now(),
                'jws' => $jws
            ];
            $student->approved_by = $approved_by;
            $student->save();

            if($request->wantsJson()){
                return response()->json([
                    'message' => 'Student record approved successfully'
                ]);
            }
            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'Student record approved successfully',
                'status' => 'success'
            ]);
        }
        catch(Exception $e){
            if($request->wantsJson()){
                return response()->json([
                    'message' => 'An error has occured while approving student record. '.$e->getMessage()
                ], 403);
            }

            return redirect()->route('viewStudent', $studentId)->with([
                'message' => 'An error has occured while approving student record. '.$e->getMessage(),
                'status' => 'error'
            ]);
        }
        
    }

    //method to show student details. This can be used for verifying student record by users
    public function showStudentDetails(Request $request, $id) {
        $student = Student::find($id);
    
        if (!$student) {
            return $this->handleStudentResponse(
                $request,
                'Student record not found',
                'warning',
                404
            );
        }
    
        if (isset($student->approved) && $student->approved == 1) {
            return $this->handleStudentResponse(
                $request,
                'Student record is approved',
                'success',
                200,
                $student
            );
        }
    
        return $this->handleStudentResponse(
            $request,
            'Student record is not approved yet',
            'warning',
            403
        );
    }

    private function handleStudentResponse($request, $message, $status, $httpCode = 200, $student = null) {
        if ($request->wantsJson()) {
            $response = ['message' => $message];
            if ($student) {
                $response['student'] = $student;
            }
            return response()->json($response, $httpCode);
        }
    
        $viewData = ['message' => $message, 'status' => $status];
        if ($student) {
            $viewData['student'] = $student;
        }
        return view('student.showStudentDetails', $viewData);
    }


    //method to edit a student record
    public function editStudent(EditStudentRequest $request, $id){
        $student = Student::find($id);
        if($request->isMethod("POST"))
        {
            //$data = $request->all();     
            $data = $request->validated(); // Use validated data from the request       
            unset($data["_token"]);
            try{
                foreach($data as $key => $value){
                    if($key == "_token"){
                        continue;
                    }
                    $student->{$key} = $value;
                }
                if(count($data) > 0){
                    $student->save();
                }
            }
            catch(Exception $e){                
                return redirect()->back()->with('error', 'An error occurred while saving student record. Please try again. '.$e->getMessage());
            }
            return redirect()->back()->with('success', 'Student record successfully.');
        }
        
        return view('student.editStudent',[
            'student' => $student
        ]);
    }

}
