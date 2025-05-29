<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function getCourses(Request $request, string $short_name = null){
        $courses = !is_null($short_name)?Course::where('short_form', $short_name)->get():Course::all();

        return response()->json([
            'courses' => $courses
        ]);
    }

    public function addCourse(Request $request){
        $course = new Course();
        $course->course_name = request('course_name');
        $course->short_name = request('short_name');
        $course->description = request('description');
        $course->save();

        return response()->json([
            'message' => 'Course added successfully',
            'course' => $request->all()
        ]);
    }
}
