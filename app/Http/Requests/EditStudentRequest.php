<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->isMethod("post") === false)
        {
            return [
            //
            ];
        }

        return [
            'student_id' => 'required',
            'Name_of_Students' => 'required',
            'छात्रों_का_नाम' => 'required',
            'Course' => 'required',
            'पाठ्यक्रम' => 'required',
            'Batch' => 'required',
            'Year' => 'required',
            'Department' => 'required',
            'विभाग' => 'required',
            'Month' => 'required',
            'महीना' => 'required',
            'Father_Name' => 'required',
            'Mother_Name' => 'required',
            'Sports' => 'nullable',
            'खेल' => 'nullable',
            'Grade' => 'required',
        ];
    }
}
