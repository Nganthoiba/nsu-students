@extends('layout.app')
@section('content')
    <div class="w-2/3 mx-auto mb-4">
        <form action="{{ route('editStudent', $student->_id) }}" id="editStudentForm" method="POST">
            @csrf
            <input type="hidden" name="student_id" value="{{ $student->_id }}">
            <div class="mb-1">
                <p>
                    You can edit record of a student for the <strong>registration number:
                        {{ $student->Registration_No }}</strong>
                    before approval by the admin. After approval, you can only view the record.
                </p>

                <!-- Display Success or Error Messages -->
                @if (session('success'))
                    <div class="relative mb-4 p-4 text-sm text-green-700 bg-green-100 border border-green-300 rounded-lg">
                        {{ session('success') }}
                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-2 text-green-700 hover:text-green-900 hover:cursor-pointer">
                            x
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="relative mb-4 p-4 text-sm text-red-700 bg-red-100 border border-red-300 rounded-lg">
                        {{ session('error') }}
                        <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-2 text-red-700 hover:text-red-900 hover:cursor-pointer">
                            &times;
                        </button>
                    </div>
                @endif

            </div>
            <div class="mt-2 mb-4">
                <label for="Name_of_Students">Name of the student:</label>
                <div class="flex">
                    <div class="w-1/2 mr-2">
                        <input type="text" name="Name_of_Students" id="Name_of_Students"
                            value="{{ old('Name_of_Students', $student->Name_of_Students) }}"
                            class="relative border rounded p-2 w-full" placeholder="Student's name in english" required>
                        <div class="error">
                            @if ($errors->has('Name_of_Students'))
                                {{ $errors->first('Name_of_Students') }}
                            @endif
                        </div>
                    </div>

                    <div class="w-1/2 ml-2">
                        <input type="text" lang="hi" dir="ltr" name="छात्रों_का_नाम"
                            value="{{ old('छात्रों_का_नाम', $student->छात्रों_का_नाम) }}"
                            class="relative border rounded p-2 w-full text-hindi" placeholder="Student's name in hindi"
                            required>
                        <div class="error">
                            @if ($errors->has('छात्रों_का_नाम'))
                                {{ $errors->first('छात्रों_का_नाम') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">

                <div class="flex justify-between">

                    <div class="w-1/2 mr-2">
                        <label for="Course">Course:</label>
                        <input type="text" name="Course" id="Course" value="{{ old('Course', $student->Course) }}"
                            class="relative border rounded p-2 w-full" placeholder="Course in english" required>
                        <div class="error">
                            @if ($errors->has('Course'))
                                {{ $errors->first('Course') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="courseInHindi">पाठ्यक्रम</label>
                        <input type="text" id="courseInHindi" name="पाठ्यक्रम"
                            value="{{ old('पाठ्यक्रम', $student->पाठ्यक्रम) }}"
                            class="relative border rounded p-2 w-full text-hindi" placeholder="Course in hindi" required>
                        <div class="error">
                            @if ($errors->has('पाठ्यक्रम'))
                                {{ $errors->first('पाठ्यक्रम') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Batch">Batch:</label>
                        <input type="text" name="Batch" id="Batch" value="{{ old('Batch', $student->Batch) }}"
                            class="relative border rounded p-2 w-full" placeholder="Batch" required>
                        <div class="error">
                            @if ($errors->has('Batch'))
                                {{ $errors->first('Batch') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="Year">Year:</label>
                        <input type="number" name="Year" id="Year" value="{{ old('Year', $student->Year) }}"
                            class="relative border rounded p-2 w-full" placeholder="Year" required>
                        <div class="error">
                            @if ($errors->has('Year'))
                                {{ $errors->first('Year') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Department">Department:</label>
                        <input type="text" name="Department" id="Department"
                            value="{{ old('Department', $student->Department) }}"
                            class="relative border rounded p-2 w-full" placeholder="Department in english" required>
                        <div class="error">
                            @if ($errors->has('Department'))
                                {{ $errors->first('Department') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="DepartmentInHindi">विभाग</label>
                        <input type="text" id="DepartmentInHindi" name="विभाग"
                            value="{{ old('विभाग', $student->विभाग) }}"
                            class="relative border rounded p-2 w-full text-hindi" placeholder="Department in hindi"
                            required>
                        <div class="error">
                            @if ($errors->has('विभाग'))
                                {{ $errors->first('विभाग') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Month">Month:</label>
                        <input type="text" name="Month" id="Month" value="{{ old('Month', $student->Month) }}"
                            class="relative border rounded p-2 w-full" placeholder="Month in english" required>
                        <div class="error">
                            @if ($errors->has('Month'))
                                {{ $errors->first('Month') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="monthInHindi">महीना</label>
                        <input type="text" id="monthInHindi" name="महीना"
                            value="{{ old('महीना', $student->महीना) }}"
                            class="relative border rounded p-2 w-full text-hindi" placeholder="Month in hindi" required>
                        <div class="error">
                            @if ($errors->has('महीना'))
                                {{ $errors->first('महीना') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-4">
                <div class="flex justify-between">
                    <div class="w-1/2 mr-2">
                        <label for="Father_Name">Father's Name:</label>
                        <input type="text" name="Father_Name" id="Father_Name"
                            value="{{ old('Father_Name', $student->Father_Name) }}"
                            class="relative border rounded p-2 w-full" placeholder="Father's name" required>
                        <div class="error">
                            @if ($errors->has('Father_Name'))
                                {{ $errors->first('Father_Name') }}
                            @endif
                        </div>
                    </div>
                    <div class="w-1/2 ml-2">
                        <label for="Mother_Name">Mother's Name:</label>
                        <input type="text" id="Mother_Name" name="Mother_Name"
                            value="{{ old('Mother_Name', $student->Mother_Name) }}"
                            class="relative border rounded p-2 w-full" placeholder="Mother's name" required>
                        <div class="error">
                            @if ($errors->has('Mother_Name'))
                                {{ $errors->first('Mother_Name') }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if ($student->sport_required == true)
                <!-- For Sports -->
                <div class="mt-2 mb-4">
                    <div class="flex justify-between">
                        <div class="w-1/2 mr-2">
                            <label for="Sports">Sports:</label>
                            <input type="text" name="Sports" id="Sports"
                                value="{{ old('Sports', $student->Sports) }}" class="relative border rounded p-2 w-full"
                                placeholder="Sports in english">
                            <div class="error">
                                @if ($errors->has('Sports'))
                                    {{ $errors->first('Sports') }}
                                @endif
                            </div>
                        </div>
                        <div class="w-1/2 ml-2">
                            <label for="sportsInHindi">खेल</label>
                            <input type="text" id="sportsInHindi" name="खेल"
                                value="{{ old('खेल', $student->खेल) }}"
                                class="relative border rounded p-2 w-full text-hindi" placeholder="Sports in hindi">
                            <div class="error">
                                @if ($errors->has('खेल'))
                                    {{ $errors->first('खेल') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Grade -->
            <div class="mt-2 mb-4">
                <label for="Grade">Grade:</label>
                <input type="text" name="Grade" id="Grade" value="{{ old('Grade', $student->Grade) }}"
                    class="relative border rounded p-2 w-full" placeholder="Grade" required>
                <div class="error">
                    @if ($errors->has('Grade'))
                        {{ $errors->first('Grade') }}
                    @endif
                </div>
            </div>
            <div class="mt-2 text-center">
                <button type="submit"
                    class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:cursor-pointer hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Update</button>
            </div>
        </form>
    </div>
@endsection
@section('javascripts')
    <script src="{{ asset('js/sanscript.js') }}"></script>
    <script>
        document.querySelectorAll('.text-hindi').forEach(function(element) {
            element.addEventListener('input', function(e) {
                const transliteratedText = Sanscript.t(e.target.value, 'itrans', 'devanagari');
                e.target.value = transliteratedText;
            });
        });
    </script>
@endsection
