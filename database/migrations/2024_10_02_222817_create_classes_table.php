<?php

use App\Models\Level;
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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string("uuid")->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignIdFor(User::class)
                ->constrained()
                ->onDelete('cascade');
            $table->integer('number_session');
            $table->boolean('presential');
            $table->string('status')->default('ongoing'); // Stocke les valeurs de l'Enum sous forme de chaîne
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
