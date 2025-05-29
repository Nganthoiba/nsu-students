<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(RegistrationRequest $request) {
        $data = $request->validated();


        /* $user = User::create([
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
         ]); */

        $user = new User();
        $user->full_name = $data['full_name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = ['staff'];// by default every new user is a staff
        $user->approved = 0; // by default every new user is not approved
        $user->save();

        Auth::login($user);

        //return redirect()->route('home')->with('success', 'Registration successful!');
        return redirect()->route('home')->with(['success' => true, 'message' => 'Registration successful!']);
    }
    public function showRegistrationForm() {
        return view('authenticate.register');
    }
}
