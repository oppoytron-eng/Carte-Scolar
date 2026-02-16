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
        Schema::create('eleves', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 50)->unique();
            $table->string('nom', 100);
            $table->string('prenoms', 100);
            $table->date('date_naissance');
            $table->string('lieu_naissance', 150);
            $table->enum('sexe', ['M', 'F']);
            $table->string('nationalite', 50)->default('Ivoirienne');
            $table->string('contact_parent', 20);
            $table->string('contact_parent_2', 20)->nullable();
            $table->string('nom_parent', 150)->nullable();
            $table->string('profession_parent', 100)->nullable();
            $table->text('adresse_parent')->nullable();
            $table->string('email_parent', 100)->nullable();
            
            // Relations
            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            
            // Métadonnées
            $table->string('annee_scolaire', 20); // Ex: 2024-2025
            $table->enum('statut', ['Actif', 'Inactif', 'Transfere', 'Abandonne'])->default('Actif');
            $table->date('date_inscription');
            $table->text('observations')->nullable();
            $table->boolean('redoublant')->default(false);
            $table->string('groupe_sanguin', 5)->nullable();
            $table->text('allergies')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour améliorer les performances
            $table->index(['etablissement_id', 'classe_id']);
            $table->index(['annee_scolaire', 'statut']);
            $table->index(['nom', 'prenoms']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eleves');
    }
};
