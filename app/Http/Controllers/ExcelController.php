<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\Auth;

class ExcelController extends Controller
{
    private $headerRow = [];
    //Method to import excel file for students
    public function importStudents(Request $request) {
        $message = '';
        $updatedMessage = '';
        $importedMessage = '';
        $insertCount = 0; 
        $updateCount = 0;
        try {
            $request->validate([
                'excel_file' => 'required|mimes:xlsx,xls'
            ]);

            $file = $request->file('excel_file');
            //Excel::import(new StudentsImport, $file);

            
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            //dd($worksheet);
            $data = [];
            foreach ($worksheet->getRowIterator() as $rowIndex => $row) {

                if ($rowIndex === 1) {
                    $this->headerRow = $this->getExcelRowData($row);
                    continue;
                }
                $rowData = $this->getExcelRowData($row);
                if(count($rowData) > 0)
                {
                    $data[] = $this->setUpKeyValuePair($this->getExcelRowData($row)); 
                }
            }
            
            // Check for duplicate Registration No
            $duplicates = collect($data)->pluck('Registration_No')->duplicates();
            if ($duplicates->isNotEmpty()) {
                throw new Exception("Duplicate Registration_Nos found");
            }

            // First update the existing data
            $existingStudentData = [];
            foreach ($data as $student) {
                $recordExist = Student::where([
                    'Registration_No' => trim($student['Registration_No']),
                    'approved' => 0
                    ])->exists();
                
                if ($recordExist) 
                {
                    $student['updated_by'] = Auth::user()->_id;
                    $existingStudentData[] = $student;
                }

            }

                        
            if(count($existingStudentData) > 0){
                $updateCount = $this->updateStudentData($existingStudentData);
                $updatedMessage = $updateCount . ' student' . ($updateCount > 1 ? 's' : '') . ' updated successfully.';
            }
            
            // Filter the data to exclude records with existing Registration_No    
            
            $freshStudentData = [];
            foreach ($data as $student) {
                if (!Student::where('Registration_No', $student['Registration_No'])->exists()) {
                    $student['importedBy'] = Auth::user()->_id;
                    $freshStudentData[] = $student;
                }
            }
            
            $insertCount = count($freshStudentData);
            
            if($insertCount > 0){
                $this->importStudentIntoDatabase($freshStudentData);
                $importedMessage = ' '.$insertCount . ' student' . ($insertCount > 1 ? 's' : '') . ' imported successfully.';
            }
            
            $message = (($insertCount + $updateCount) === 0)?'No record affected.':trim($importedMessage.' '.$updatedMessage);

            if($request->wantsJson()) {
                return response()->json([
                    'message' => $message,
                    'data' => $data
                ]);
            }
            return redirect()->back()->with([
                'message' => $message,
                'success' => true
            ]);

        } catch (\Exception $e) {
            if($request->wantsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'error' => true
                ], 403);
            }

            return redirect()->back()->with([
                'message' => 'Sorry an error has occured. '.$e->getMessage(),
                'success' => false,
                'error' => true
            ]);
        }
    }

    private function getExcelRowData($row) {
        $rowData = [];
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(true);
        foreach ($cellIterator as $cell) {
            $rowData[] = $cell->getValue();
        }
        return $rowData;
    }

    private function setUpKeyValuePair($data) {

        
        if(is_array($data)){
            
            $keyValuePair = [];
            foreach ($this->headerRow as $index => $header) {
                //$header = str_replace(' ', '_', $header);
                $header = trim($header);
                $header = preg_replace('/\s+/', '_', $header);
                $header = str_replace('.', '', $header);

                if(isset($data[$index])){
                    $keyValuePair[$header] = trim($data[$index]);
                }
                else{
                    $keyValuePair[$header] = ''; 
                }
                
            }
            return $keyValuePair;
        }
        return $data;
    }


    // Insert New Students
    private function importStudentIntoDatabase($data) {
        // Prepare the data for bulk insert        
        $studentsData = array_map(function ($data) {
            /*
            return [
                'Registration_No' => trim(strval($data['Registration_No'])),
                'Name_of_Students' => trim($data['Name_of_Students'] ?? ''),
                'छात्रों_का_नाम' => trim($data['छात्रों_का_नाम'] ?? ''),
                'Gender' => trim($data['Gender'] ?? ''),
                'Father_Name' => trim($data['Father_Name'] ?? ''),
                'Mother_Name' => trim($data['Mother_Name'] ?? ''),
                'Batch' => trim($data['Batch'] ?? ''),
                'Month' => trim($data['Month'] ?? ''),
                'महीना' => trim($data['महीना'] ?? ''),
                'Year' => intval($data['Year'] ?? 0),
                'Course' => trim($data['Course'] ?? ''),
                'Department' => trim($data['Department'] ?? ''),
                'विभाग' => trim($data['विभाग'] ?? ''),
                'Sports' => trim($data['Sports'] ?? ''),
                'खेल' => trim($data['खेल'] ?? ''),
                'Grade' => trim($data['Grade'] ?? ''),
                'Sl_No' => trim($data['Sl_No'] ?? ''),
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => Auth::user()->_id,
            ];
            */
            // Check if the course is a sports course
            // Retrieve sport required courses from the config
            $sport_required_courses = config('sports.sport_required_courses');

            // Check if the course is a sports course
            $sport_required = in_array($data['Course'], $sport_required_courses);
            
            return array_merge($data, [
                'approved' => 0,
                'created_by' => Auth::user()->_id,
                'sport_required' => $sport_required,
            ]);
        }, $data);

        if (!empty($studentsData)) {
            Student::insert($studentsData);
        }
    }

    // Update Existing Students
    private function updateStudentData($dataList) {

        $studentsData = array_map(function ($data) {
            /*
            return [
                'Registration_No' => trim(strval($data['Registration_No'])),
                'Name_of_Students' => trim($data['Name_of_Students'] ?? ''),
                'छात्रों_का_नाम' => trim($data['छात्रों_का_नाम'] ?? ''),
                'Gender' => trim($data['Gender'] ?? ''),
                'Father_Name' => trim($data['Father_Name'] ?? ''),
                'Mother_Name' => trim($data['Mother_Name'] ?? ''),
                'Batch' => trim($data['Batch'] ?? ''),
                'Month' => trim($data['Month'] ?? ''),
                'महीना' => trim($data['महीना'] ?? ''),
                'Year' => intval($data['Year'] ?? 0),
                'Course' => trim($data['Course'] ?? ''),
                'Department' => trim($data['Department'] ?? ''),
                'विभाग' => trim($data['विभाग'] ?? ''),
                'Sports' => trim($data['Sports'] ?? ''),
                'खेल' => trim($data['खेल'] ?? ''),
                'Grade' => trim($data['Grade'] ?? ''),
                'Sl_No' => trim($data['Sl_No'] ?? ''),
                'updated_at' => now(),
                'updated_by' => Auth::user()->_id,
            ];
            */
            return array_merge($data, [
                'updated_at' => now(),
                'updated_by' => Auth::user()->_id,
            ]);
        }, $dataList);

        if (!empty($studentsData)) {
            return Student::upsert($studentsData, ['Registration_No'], [
                'Name_of_Students', 'छात्रों_का_नाम', 'Gender', 'Father_Name', 'Mother_Name', 
                'Batch', 'Month', 'महीना', 'Year', 'Course', 'Department', 
                'विभाग', 'Sports', 'खेल', 'Grade', 'updated_at', 'Sl_No', 'updated_by'
            ]);
        }
        return 0; //means no data has been affected
    }

    public function importStudentRecords(Request $request){
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls'
        ]);
        try{
            $import = new StudentsImport();
            Excel::import($import, $request->file('excel_file'));
            $updatedMessage = ($import->updatedCount > 0)?"{$import->updatedCount} record(s) have been updated":'';
            $importedMessage = ($import->importedCount > 0)?"{$import->importedCount} record(s) have been imported":'';
            return back()->with([
                'success'=>true, 
                'message'=>trim($importedMessage.' '.$updatedMessage)
            ]);
        }
        catch(Exception $e){
            return back()->with([
                'success'=>false, 
                'message'=>'An error has occured while importing student records form the uploaded excel file. '.$e->getMessage(),
                'error'=>$e
            ]);
            
        }
        
    }

}
