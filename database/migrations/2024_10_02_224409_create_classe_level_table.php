<?php

use App\Models\Classe;
use App\Models\Level;
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
        Schema::create('classe_level', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Classe::class)
                ->constrained()
                ->onDelete('cascade');
            $table->foreignIdFor(Level::class)
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
        Schema::dropIfExists('classe_level');
    }
};
