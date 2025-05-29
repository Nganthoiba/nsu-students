@extends('layout.app')
@section('content')
    <div class="flex w-full lg:w-2/3 lg:pr-8 mx-auto mt-2 justify-between">
        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 mr-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Dashboard</h2>
            <p class="text-sm text-gray-600">Welcome to the dashboard! Here you can manage your students and their records.
            </p>

            <div class="mt-2">
                <h5 class="font-medium text-gray-700">Number of Registered Students</h5>

                <ul class="text-gray-500 space-y-1 flex gap-4 justify-around">
                    <li class="flex flex-col items-center justify-center p-4">
                        <i class="fa-light fa fa-user-graduate"></i>
                        <span class="font-semibold">{{ $studentCount }}</span>
                        <span class="text-xs text-gray-500"><a href="{{ route('displayStudents') }}">Total</a></span>
                    </li>

                    <li class="flex flex-col items-center justify-center p-4">
                        <i class="fa-light fa fa-user-graduate"></i>
                        <span class="font-semibold">{{ $pendingCount }}</span>
                        <span class="text-xs text-blue-500"><a href="{{ route('displayStudents', 'pending') }}">Pending
                                ...</a></span>
                    </li>

                    <li class="flex flex-col items-center justify-center p-4">
                        <i class="fa-light fa fa-user-graduate"></i>
                        <span class="font-semibold">{{ $approvedCount }}</span>
                        <span class="text-xs text-green-500"><a
                                href="{{ route('displayStudents', 'signed') }}">Approved<a></span>
                    </li>
                </ul>
            </div>
            <div>
                <a href="{{ route('displayStudents') }}" class="text-sm text-blue-400 hover:text-blue-600">
                    <i class="fa-light fas fa-arrow-right"></i>
                    See the list of registered students</a>
            </div>
        </div>
        <form action="{{ route('excel.importStudents') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 ml-2">
                <h2 class="text-lg font-bold mb-4">Import Students</h2>
                <label for="file" class="text-sm font-medium text-gray-900 dark:text-gray-300">Upload Excel File</label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Please upload a valid excel file to import
                    students'
                    record.</p>
                <input type="file" name="excel_file" id="file"
                    class="border border-gray-300 p-2 rounded-lg relative w-full mt-2" required>
                <br>
                <div class="error">

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    @endif
                </div>
                <div class="text-right mt-4">
                    <button type="submit"
                        class="focus:outline-none text-white bg-purple-700 hover:bg-purple-800 focus:ring-4
                    focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-purple-600
                    dark:hover:bg-purple-700 dark:focus:ring-purple-900 cursor-pointer">Submit</button>
                </div>
                <div class="success">
                    @if (session('success'))
                        <div role="alert"
                            class="mb-4 relative flex w-full p-3 text-sm text-white bg-green-600 rounded-md">
                            {{ session('message') }}
                            <button onclick="this.parentElement.remove()"
                                class="flex items-center justify-center transition-all w-8 h-8 rounded-md text-white hover:bg-white/10 active:bg-white/10 absolute top-1.5 right-1.5 cursor-pointer"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="error">
                    @if (session('error'))
                        <div role="alert" class="mb-4 relative flex w-full p-3 text-sm text-white bg-red-600 rounded-md">
                            {{ session('message') }}
                            <button onclick="this.parentElement.remove()"
                                class="flex items-center justify-center transition-all w-8 h-8 rounded-md text-white hover:bg-white/10 active:bg-white/10 absolute top-1.5 right-1.5 cursor-pointer"
                                type="button">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" class="h-5 w-5" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
