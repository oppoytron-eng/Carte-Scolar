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
        // Table des médias associés aux cartes
        Schema::create('medias_carte', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carte_scolaire_id')->constrained('cartes_scolaires')->onDelete('cascade');
            $table->string('nom', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['Logo', 'Signature', 'Tampon', 'Fond', 'Autre'])->default('Autre');
            $table->string('chemin', 255);
            $table->string('format', 20); // jpg, png, pdf
            $table->integer('taille')->nullable(); // En KB
            $table->integer('ordre')->default(0); // Pour l'ordre d'affichage
            $table->timestamps();
        });

        // Table des notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type', 100); // Type de notification
            $table->string('titre', 255);
            $table->text('message');
            $table->json('data')->nullable(); // Données supplémentaires
            $table->enum('priorite', ['Basse', 'Normale', 'Haute', 'Urgente'])->default('Normale');
            $table->timestamp('date_notification');
            $table->boolean('est_lu')->default(false);
            $table->timestamp('date_lecture')->nullable();
            $table->string('lien', 255)->nullable(); // Lien d'action
            $table->string('icone', 50)->nullable(); // Icône FontAwesome
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'est_lu']);
            $table->index('date_notification');
        });

        // Table du journal d'audit (actions)
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type_action', 100); // create, update, delete, login, etc.
            $table->string('module', 100); // eleve, carte, photo, etc.
            $table->string('description', 500);
            $table->string('entite_type', 100)->nullable(); // Type de l'entité concernée
            $table->unsignedBigInteger('entite_id')->nullable(); // ID de l'entité
            $table->json('donnees_avant')->nullable(); // État avant modification
            $table->json('donnees_apres')->nullable(); // État après modification
            $table->string('adresse_ip', 45);
            $table->text('user_agent')->nullable();
            $table->enum('statut', ['Succes', 'Echec', 'En_cours'])->default('Succes');
            $table->text('message_erreur')->nullable();
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'created_at']);
            $table->index(['entite_type', 'entite_id']);
            $table->index('type_action');
            $table->index('created_at');
        });

        // Table pivot users-etablissements
        Schema::create('etablissement_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('etablissement_id')->constrained('etablissements')->onDelete('cascade');
            $table->enum('role_etablissement', [
                'Proviseur',
                'Surveillant_General',
                'Operateur_Photo',
                'Secretaire'
            ]);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('is_principal')->default(false); // Établissement principal de l'utilisateur
            $table->timestamps();
            
            // Contrainte unique
            $table->unique(['user_id', 'etablissement_id']);
        });

        // Table des horaires/heures (pour la planification)
        Schema::create('horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('classe_id')->constrained('classes')->onDelete('cascade');
            $table->string('jour_semaine', 20); // Lundi, Mardi, etc.
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->string('activite', 100); // Cours, Pause, etc.
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horaires');
        Schema::dropIfExists('etablissement_user');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('medias_carte');
    }
};
