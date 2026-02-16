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
        Schema::create('cartes_scolaires', function (Blueprint $table) {
            $table->id();
            $table->string('numero_carte', 50)->unique(); // Numéro unique de la carte
            
            // Relations
            $table->foreignId('eleve_id')->constrained('eleves')->onDelete('cascade');
            $table->foreignId('photo_id')->constrained('photos')->onDelete('cascade');
            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade');
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            
            // Informations de génération
            $table->foreignId('generateur_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_generation');
            $table->string('annee_scolaire', 20); // Ex: 2024-2025
            
            // Fichiers générés
            $table->string('chemin_recto', 255)->nullable(); // PDF/Image recto
            $table->string('chemin_verso', 255)->nullable(); // PDF/Image verso
            $table->string('chemin_pdf', 255)->nullable(); // PDF complet
            
            // QR Code et Code-barres
            $table->string('qr_code_data', 500); // Données encodées dans le QR
            $table->string('chemin_qr_code', 255)->nullable();
            $table->string('code_barres', 100)->nullable();
            
            // Statut du workflow
            $table->enum('statut', [
                'Photo_prise',
                'Informations_validees',
                'Carte_generee',
                'Carte_imprimee',
                'Carte_distribuee',
                'Perdue',
                'Annulee'
            ])->default('Photo_prise');
            
            // Impression
            $table->timestamp('date_impression')->nullable();
            $table->foreignId('imprimeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('nombre_impressions')->default(0);
            
            // Distribution
            $table->timestamp('date_distribution')->nullable();
            $table->foreignId('distributeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('signature_parent')->nullable(); // Chemin vers la signature
            
            // Modèle utilisé
            $table->string('modele_carte', 50)->default('standard'); // standard, premium, etc.
            $table->json('donnees_carte')->nullable(); // JSON avec les données de la carte
            
            // Validité
            $table->date('date_debut_validite');
            $table->date('date_fin_validite');
            $table->boolean('est_valide')->default(true);
            
            // Métadonnées
            $table->text('observations')->nullable();
            $table->boolean('is_duplicate')->default(false); // Si c'est un duplicata
            $table->foreignId('carte_originale_id')->nullable()->constrained('cartes_scolaires')->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Index
            $table->index(['eleve_id', 'annee_scolaire']);
            $table->index(['etablissement_id', 'statut']);
            $table->index('statut');
            $table->index('date_generation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cartes_scolaires');
    }
};
