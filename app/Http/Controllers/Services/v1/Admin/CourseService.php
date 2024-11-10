<?php

namespace App\Http\Controllers\Services\v1\Admin;

use App\Http\Resources\v1\Admin\CourseResource;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CourseService
{
    public function getAllCourses()
    {
        $courses = Course::with('classes')->get();
        return CourseResource::collection($courses);
    }

    public function getCourseById($id)
    {
        $course = Course::with('classes')->findOrFail($id);
        return new CourseResource($course);
    }

    public function createCourse($data)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin') && !$user->hasRole('teacher')) {
            throw new \Exception("Vous n'avez pas les droits d'accès à cette action.");
        }

        DB::beginTransaction();
        try {
            // Si un fichier PDF est fourni, stocke-le avec le nom original inclus dans le chemin
            if (isset($data['pdf'])) {
                $originalName = pathinfo($data['pdf']->getClientOriginalName(), PATHINFO_FILENAME);
                $pdfPath = $data['pdf']->storeAs('courses/pdfs', $originalName . '_' . time() . '.' . $data['pdf']->extension(), 'public');
            } else {
                $pdfPath = null;
            }

            // Créer le cours en incluant le chemin du PDF
            $course = Course::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'pdf_path' => $pdfPath,
                'user_id' => $user->id,
            ]);

            if (isset($data['class_ids'])) {
                $course->classes()->attach($data['class_ids']);
            }

            DB::commit();
            return new CourseResource($course);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erreur lors de la création du cours : " . $e->getMessage());
        }
    }

    public function updateCourse($data, $id)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin') && !$user->hasRole('teacher')) {
            throw new \Exception("Vous n'avez pas les droits d'accès à cette action.");
        }

        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);

            if ($user->hasRole('teacher') && $course->user_id !== $user->id) {
                throw new \Exception("Vous n'êtes pas autorisé à mettre à jour ce cours.");
            }

            // Si un nouveau fichier PDF est fourni, remplace l'ancien fichier et conserve le nom original
            if (isset($data['pdf'])) {
                if ($course->pdf_path) {
                    Storage::disk('public')->delete($course->pdf_path);
                }
                $originalName = pathinfo($data['pdf']->getClientOriginalName(), PATHINFO_FILENAME);
                $course->pdf_path = $data['pdf']->storeAs('courses/pdfs', $originalName . '_' . time() . '.' . $data['pdf']->extension(), 'public');
            }

            $course->update([
                'name' => $data['name'],
                'description' => $data['description'],
            ]);

            if (isset($data['class_ids'])) {
                $course->classes()->sync($data['class_ids']);
            }

            DB::commit();
            return new CourseResource($course);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erreur lors de la mise à jour du cours : " . $e->getMessage());
        }
    }

    public function deleteCourse($id)
    {
        $user = Auth::user();

        // Vérification des rôles pour s'assurer que seul un admin ou un enseignant peut supprimer un cours
        if (!$user->hasRole('admin') && !$user->hasRole('teacher')) {
            throw new \Exception("Vous n'avez pas les droits d'accès à cette action.");
        }

        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);

            // Vérifie si l'utilisateur est bien le propriétaire du cours ou un admin
            if ($user->hasRole('teacher') && $course->user_id !== $user->id) {
                throw new \Exception("Vous n'êtes pas autorisé à supprimer ce cours.");
            }

            // Suppression des fichiers associés
            if ($course->image_path) {
                Storage::disk('public')->delete($course->image_path);
            }

            if ($course->pdf_path) {
                Storage::disk('public')->delete($course->pdf_path);
            }

            // Détacher toutes les classes avant de supprimer le cours
            $course->classes()->detach();

            // Supprimer le cours
            $course->delete();

            DB::commit();
            return response()->json(['message' => 'Cours supprimé avec succès.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("Erreur lors de la suppression du cours : " . $e->getMessage());
        }
    }
}
