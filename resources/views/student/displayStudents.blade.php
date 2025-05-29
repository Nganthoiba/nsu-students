@extends('layout.app')
@section('content')
    {{-- get headings first --}}
    @php
        //$headings = count($students) > 0 ? array_keys($students[0]->toArray()) : [];
        $headings = [
            //'Sl_No',
            ['Name_of_Students', 'छात्रों_का_नाम'],
            'Gender',
            // 'Father_Name',
            // 'Mother_Name',
            'Batch',
            'Registration_No',
            ['Month', 'महीना'],
            'Year',
            ['Course', 'पाठ्यक्रम'],
            ['Department', 'विभाग'],
            ['Sports', 'खेल'],
            'Grade',
        ];

        $sl_no = ($students->currentPage() - 1) * $students->perPage() + 1;
    @endphp

    <div class="mb-1 flex justify-between">
        <h4 class="text-bold">List of registered students:</h4>
        <div class="flex">
            @php
                $allButtonActive = $status == '' ? 'active' : '';
                $signedButtonActive = $status == 'signed' ? 'active' : '';
                $pendingButtonActive = $status == 'pending' ? 'active' : '';
            @endphp
            <a href="{{ route('displayStudents') }}" class="selector {{ $allButtonActive }}">All</a>
            <a href="{{ route('displayStudents', 'signed') }}" class="selector {{ $signedButtonActive }}">Signed</a>
            <a href="{{ route('displayStudents', 'pending') }}" class="selector {{ $pendingButtonActive }}">Pending</a>
        </div>
        <form method="GET" action="{{ route('displayStudents') }}">
            <div class="relative">
                <input type="search" id="location-search"
                    class="border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2"
                    name="search" value="{{ request('search') }}" placeholder="Search students..." />
                <button type="submit"
                    class="absolute top-0 end-0 h-full p-2.5 text-sm font-medium text-white 
                bg-blue-700 rounded-e-lg border
                border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none 
                focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 
                dark:focus:ring-blue-800">

                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                    <span class="sr-only">Search</span>
                </button>
            </div>

        </form>
    </div>
    @if (session('status'))
        <div class="text-center px-6">
            <div role="alert" class="mb-4 relative flex p-3 text-sm text-white bg-red-600 rounded-md">
                {{ session('message') }}
                <button onclick="this.parentElement.remove()"
                    class="flex items-center justify-center transition-all w-8 h-8 rounded-md text-white hover:bg-white/10 active:bg-white/10 absolute top-1.5 right-1.5 cursor-pointer"
                    type="button">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        class="h-5 w-5" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- display students --}}
    @if (count($students) > 0)
        <div class="relative overflow-x-auto">
            {{-- table-auto w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 border-collapse border border-gray-400 --}}
            <table class="w-full text-xs text-left text-gray-900 border-collapse border border-gray-300">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="border-b border border-gray-300">
                        <th scope="col" class="px-3 py-3">Sl. No.</th>
                        @foreach ($headings as $heading)
                            @if (is_array($heading))
                                <th scope="col" class="px-3 py-3">
                                    {{ str_replace('_', ' ', $heading[0]) }}
                                </th>
                            @else
                                <th scope="col" class="px-3 py-3">
                                    {{ str_replace('_', ' ', $heading) }}
                                </th>
                            @endif
                        @endforeach
                        <th>Status</th><!-- Status Column -->
                        <th></th><!-- Action Column -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $key => $student)
                        <tr class="border-b border border-gray-300" style="margin: 3px;">
                            <td class="px-3 py-1">{{ $key + $sl_no }}</td>
                            @foreach ($headings as $heading)
                                <td class="px-3 py-1">
                                    @if (is_array($heading))
                                        @foreach ($heading as $subheading)
                                            <div>{{ $student[$subheading] ?? '' }}</div>
                                        @endforeach
                                    @else
                                        {{ $student[$heading] ?? '' }}
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                @if (!$student->isSigned())
                                    <span class="px-2 py-1 bg-blue-500 text-white rounded-md">Pending</span>
                                @else
                                    <span class="px-2 py-1 bg-green-500 text-white rounded-md">Signed</span>
                                @endif
                            </td>
                            <td class="px-3" style="padding: 6px; ">
                                <a href="{{ route('viewStudent', $student->_id) }}?page={{ $page }}&search={{ $search }}&status={{ $status }}"
                                    class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="my-3">{{ $students->appends(['search' => request('search')])->links() }} {{-- Display pagination links --}}
            </div>
        </div>
    @else
        <div class="flex justify-centerbg-gray-100">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mx-auto" role="alert">
                <strong class="font-bold">Alert!</strong>
                <span class="block sm:inline">No students found.</span>
            </div>
        </div>
    @endif
@endsection
