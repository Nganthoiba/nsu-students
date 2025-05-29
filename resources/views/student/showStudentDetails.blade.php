@extends('layout.app')
@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Student Details</h2>
        @if ($status == 'success')
            <table class="w-full border-collapse border border-gray-300">
                <tr>
                    <td class="p-2 font-semibold border">Sl. No</td>
                    <td class="p-2 border">{{ $student->Sl_No }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Name</td>
                    <td class="p-2 border">{{ $student->Name_of_Students }} ({{ $student->छात्रों_का_नाम }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Registration No</td>
                    <td class="p-2 border">{{ $student->Registration_No }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Course</td>
                    <td class="p-2 border">{{ $student->Course }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Batch</td>
                    <td class="p-2 border">{{ $student->Batch }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Department</td>
                    <td class="p-2 border">{{ $student->Department }} ({{ $student->Department_In_Hindi }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Month</td>
                    <td class="p-2 border">{{ $student->Month }} ({{ $student->महीना }})</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Year</td>
                    <td class="p-2 border">{{ $student->Year }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Father's Name</td>
                    <td class="p-2 border">{{ $student->Father_Name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Mother's Name</td>
                    <td class="p-2 border">{{ $student->Mother_Name }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Gender</td>
                    <td class="p-2 border">{{ $student->Gender }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Grade</td>
                    <td class="p-2 border">{{ $student->Grade }}</td>
                </tr>
                <tr>
                    <td class="p-2 font-semibold border">Sports</td>
                    <td class="p-2 border">{{ $student->Sports ?? 'N/A' }} ({{ $student->खेल ?? 'N/A' }})</td>
                </tr>
            </table>
        @endif

        <div class="mt-2">
            @isset($message)
                @php
                    $textClass = $status == 'error' || $status == 'warning' ? 'text-red-500' : 'text-green-500';
                @endphp
                <p class="{{ $textClass }}">{{ $message }}</p>
            @endisset
        </div>
    </div>
@endsection
