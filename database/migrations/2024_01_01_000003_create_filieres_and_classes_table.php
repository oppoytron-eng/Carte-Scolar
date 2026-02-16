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
        Schema::create('filieres', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100);
            $table->string('code', 20)->unique();
            $table->text('description')->nullable();
            $table->enum('niveau', ['College', 'Lycee'])->default('Lycee');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 100); // Ex: 6ème A, Terminale S1
            $table->enum('niveau', [
                // Primaire
                'CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2',
                // Collège
                '6eme', '5eme', '4eme', '3eme',
                // Lycée
                'Seconde', 'Premiere', 'Terminale'
            ]);
            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade');
            $table->foreignId('filiere_id')->nullable()->constrained('filieres')->onDelete('set null');
            $table->string('salle', 50)->nullable();
            $table->integer('effectif_max')->default(60);
            $table->integer('effectif_actuel')->default(0);
            $table->string('annee_scolaire', 20); // Ex: 2024-2025
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour optimiser les recherches
            $table->index(['etablissement_id', 'annee_scolaire']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
        Schema::dropIfExists('filieres');
    }
};
