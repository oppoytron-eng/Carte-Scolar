<?php

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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 200);
            $table->enum('type', ['Primaire', 'College', 'Lycee', 'Mixte'])->default('Mixte');
            $table->string('localisation', 255);
            $table->string('ville', 100);
            $table->string('commune', 100)->nullable();
            $table->enum('grade', ['Public', 'Prive'])->default('Public');
            $table->string('code_etablissement', 20)->unique();
            $table->string('logo')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->text('adresse')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};
