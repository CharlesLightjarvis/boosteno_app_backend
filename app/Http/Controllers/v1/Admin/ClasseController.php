<?php

namespace App\Http\Controllers\v1\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Services\v1\Admin\ClasseService;
use App\Http\Requests\Admin\ClasseRequest;
use App\Http\Resources\v1\Admin\ClasseResource;
use App\Models\Classe;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClasseController extends BaseController
{

    protected $classeService;

    public function __construct(ClasseService $classeService)
    {
        $this->classeService = $classeService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = $this->classeService->getAllClasses();
        return $this->sendResponse($classes, "Classes retrieved successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClasseRequest $request)
    {
        $classe = $this->classeService->createClasse($request->validated());
        return $this->sendResponse($classe, "Classe created successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classe = $this->classeService->getClasseById($id);
        return $this->sendResponse($classe, "Classe retrieved successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClasseRequest $request, $id)
    {
        $classe = $this->classeService->updateClasse($request->validated(), $id);
        return $this->sendResponse($classe, "Classe updated successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $classe = $this->classeService->deleteClasse($id);
        return $this->sendResponse($classe, "Classe deleted successfully");
    }

    public function addStudentsToClasse(Request $request)
    {
        // Récupérer le classe_id et les student_ids du JSON
        $classeId = $request->input('classe_id');
        $studentIds = $request->input('student_ids');

        $classe = Classe::findOrFail($classeId);

        // Vérifier que les IDs sont bien des étudiants
        $students = User::whereIn('id', $studentIds)->get();
        foreach ($students as $student) {
            if (!$student->hasRole('student')) {
                throw new Exception("L'utilisateur avec l'ID {$student->id} n'a pas le rôle d'étudiant.");
            }
        }

        // Ajouter les étudiants à la classe
        $classe->students()->attach($studentIds);

        // Retourner la classe mise à jour avec les étudiants
        return $this->sendResponse(new ClasseResource($classe->load(['teacher', 'students', 'levels'])), "Étudiants ajoutés à la classe avec succès");
    }


    public function removeStudentsFromClasse(Request $request)
    {
        // Récupérer le classe_id et les student_ids du JSON
        $classeId = $request->input('classe_id');
        $studentIds = $request->input('student_ids');

        $classe = Classe::findOrFail($classeId);

        // Vérifier que les IDs sont bien des étudiants
        $students = User::whereIn('id', $studentIds)->get();
        foreach ($students as $student) {
            if (!$student->hasRole('student')) {
                throw new Exception("L'utilisateur avec l'ID {$student->id} n'a pas le rôle d'étudiant.");
            }
        }

        // Retirer les étudiants de la classe
        $classe->students()->detach($studentIds);

        // Retourner la classe mise à jour avec les étudiants
        return $this->sendResponse(new ClasseResource($classe->load(['teacher', 'students', 'levels'])), "Étudiants retirés de la classe avec succès");
    }

    // public function studentsNotSameLevelAssign($level)
    // {
    //     // Récupère tous les IDs des classes associées à ce niveau
    //     $classesWithLevel = DB::table('classe_level')
    //         ->where('level_id', $level)
    //         ->pluck('classe_id'); // Récupère les IDs des classes de ce niveau

    //     // Récupère tous les IDs des étudiants déjà assignés à ces classes
    //     $assignedStudents = DB::table('classe_user')
    //         ->whereIn('classe_id', $classesWithLevel)
    //         ->pluck('user_id') // Récupère les IDs des étudiants assignés
    //         ->unique() // Évite les doublons
    //         ->toArray(); // Convertit en tableau

    //     // Récupère les étudiants qui ne sont pas encore assignés à ces classes
    //     return User::whereNotIn('id', $assignedStudents)
    //         ->whereHas('roles', function ($query) {
    //             $query->where('name', 'student');
    //         })
    //         ->get();
    // }

    public function studentsNotSameLevelAssign($level)
    {
        // Récupère les étudiants qui ne sont pas assignés aux classes du niveau donné
        $studentsNotInLevel = User::whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })
            ->whereNotIn('id', function ($query) use ($level) {
                $query->select('user_id')
                    ->from('classe_user')
                    ->whereIn('classe_id', function ($subQuery) use ($level) {
                        $subQuery->select('classe_id')
                            ->from('classe_level')
                            ->where('level_id', $level);
                    });
            })
            ->get();

        return $studentsNotInLevel;
    }
}
