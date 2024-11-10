<?php

use App\Models\Classe;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('classe_course', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Classe::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(Course::class)
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classe_course');
    }
};
