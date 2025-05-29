<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class HomeController extends Controller
{
    public function showDashboard() {
        $sudentCount = Student::count();
        $approvedCount = Student::where('approved', 1)->count();
        $pendingCount = Student::where('approved', 0)->count();
        return view('home.dashboard')->with([
            'studentCount' => $sudentCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
        ]);
    }

    public function testApi(){
        return response()->json([
            'message' => 'Hello World!'
        ]);
    }
}
