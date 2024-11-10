<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Services\v1\Admin\CourseService;
use App\Http\Requests\Admin\CourseRequest;
use App\Http\Resources\v1\Admin\CourseResource;
use App\Models\Classe;

class CourseController extends BaseController
{

    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->courseService->getAllCourses();
        return $this->sendResponse($courses, "Classes retrieved successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $course = $this->courseService->createCourse($request->validated());
        return $this->sendResponse($course, "Course created successfully", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = $this->courseService->getCourseById($id);
        return $this->sendResponse($course, "Course retrieved successfully");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request,  $id)
    {
        $course = $this->courseService->updateCourse($request->validated(), $id);
        return $this->sendResponse($course, "Course updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->courseService->deleteCourse($id);
        return $this->sendResponse([], "Course deleted successfully");
    }

    public function getClasseCourses($id)
    {
        $classe = Classe::findOrFail($id);

        // Utiliser la méthode `collection` pour transformer une collection de cours
        $courses = CourseResource::collection($classe->courses()->get());
        return $this->sendResponse($courses, "Courses retrieved successfully");
    }
}
