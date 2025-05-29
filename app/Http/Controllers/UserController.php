<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function createUser(){
        $user = new User();
        $user->full_name = 'John Doe';
        $user->email = 'john@gmail.com';
        $user->password = 'Test@123';
        $user->save();
        return response()->json($user);
    }

    //Reset all passwords
    public function resetAllPasswords() {
        $users = User::all();
        foreach ($users as $user) {
            $user->password = Hash::make('Test@123');
            $user->save();
        }
        return response()->json(['message' => 'All passwords have been reset to Test@123']);
    }
}
