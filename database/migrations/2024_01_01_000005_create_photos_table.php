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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('photo_id', 100)->unique(); // Identifiant unique de la photo
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('operateur_id')->constrained('users')->onDelete('cascade');
            
            // Informations de la photo
            $table->string('chemin_original', 255); // Chemin du fichier original
            $table->string('chemin_redimensionne', 255)->nullable(); // Photo recadrée
            $table->string('chemin_miniature', 255)->nullable(); // Miniature
            $table->string('format', 10)->default('jpg'); // jpg, png
            $table->integer('largeur')->nullable();
            $table->integer('hauteur')->nullable();
            $table->integer('taille_fichier')->nullable(); // En KB
            
            // Métadonnées de capture
            $table->enum('methode_capture', ['Webcam', 'Upload', 'Smartphone', 'Tablette'])->default('Webcam');
            $table->string('appareil', 100)->nullable();
            $table->timestamp('date_capture');
            
            // Statut et validation
            $table->enum('statut', [
                'En_attente',
                'Approuvee',
                'Rejetee',
                'A_refaire'
            ])->default('En_attente');
            
            $table->foreignId('validateur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('date_validation')->nullable();
            $table->text('motif_rejet')->nullable();
            
            // Qualité de la photo
            $table->integer('score_qualite')->nullable(); // Score de 0 à 100
            $table->boolean('visage_detecte')->default(false);
            $table->boolean('yeux_ouverts')->default(false);
            $table->boolean('luminosite_correcte')->default(false);
            $table->boolean('fond_uniforme')->default(false);
            
            // Traçabilité
            $table->boolean('is_active')->default(true);
            $table->integer('version')->default(1); // Pour gérer plusieurs versions
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['eleve_id', 'statut']);
            $table->index(['operateur_id', 'date_capture']);
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
