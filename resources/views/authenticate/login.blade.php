@extends('layout.app')
@section('content')
    <div class="flex flex-col w-full lg:w-1/3 lg:pr-8 mx-auto mt-2">
        <h1 class="text-2xl font-bold mb-4">Login</h1>
        <form method="POST" action="{{ route('login') }}" class="flex flex-col space-y-4">
            @csrf
            <div class="flex flex-col space-y-1">
                <label for="email" class="text-sm font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                    autofocus
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error text-sm">
                    @if ($errors->has('email'))
                        {{ $errors->first('email') }}
                    @endif
                </div>
            </div>
            <div class="flex flex-col space-y-1">
                <label for="password" class="text-sm font-medium">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="rounded-lg border border-gray-300 focus:border-gray-500 focus:ring-1 focus:ring-gray-500 focus:outline-none p-2">
                <div class="error text-sm">
                    @if ($errors->has('password'))
                        {{ $errors->first('password') }}
                    @endif
                </div>
            </div>
            <div class="mt-1 text-end">
                <a href="#" class="text-blue-500 hover:text-blue-700">Forgot Password?</a>
            </div>
            <div class="mt-4">
                <button type="submit"
                    class="bg-gray-800 text-white rounded-lg py-2 transition-colors duration-200 hover:bg-gray-700 w-full cursor-pointer">Login</button>
            </div>

        </form>
    </div>
@endsection
