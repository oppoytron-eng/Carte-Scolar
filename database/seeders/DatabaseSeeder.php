<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Etablissement;
use App\Models\Filiere;
use App\Models\Classe;
use App\Models\Eleve;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les utilisateurs par rôle
        $this->createUsers();
        
        // Créer les filières
        $this->createFilieres();
        
        // Créer les établissements
        $etablissements = $this->createEtablissements();
        
        // Créer les classes pour chaque établissement
        foreach ($etablissements as $etablissement) {
            $this->createClasses($etablissement);
        }
        
        // Créer des élèves de test
        $this->createEleves();
        
        $this->command->info('Base de données initialisée avec succès!');
    }

    private function createUsers(): void
    {
        $this->command->info('Création des utilisateurs...');
        
        // Administrateur
        $admin = User::create([
            'nom' => 'KOUASSI',
            'prenoms' => 'Jean Admin',
            'email' => 'admin@cartescolaire.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'Administrateur',
            'phone' => '+225 07 07 07 07 07',
            'is_active' => true,
        ]);
        $this->command->info("✓ Administrateur créé: {$admin->email}");

        // Proviseur
        $proviseur = User::create([
            'nom' => 'DIALLO',
            'prenoms' => 'Marie Proviseur',
            'email' => 'proviseur@cartescolaire.com',
            'password' => Hash::make('Proviseur@123'),
            'role' => 'Proviseur',
            'phone' => '+225 07 08 08 08 08',
            'is_active' => true,
        ]);
        $this->command->info("✓ Proviseur créé: {$proviseur->email}");

        // Surveillant Général
        $surveillant = User::create([
            'nom' => 'KONE',
            'prenoms' => 'Pierre Surveillant',
            'email' => 'surveillant@cartescolaire.com',
            'password' => Hash::make('Surveillant@123'),
            'role' => 'Surveillant General',
            'phone' => '+225 07 09 09 09 09',
            'is_active' => true,
        ]);
        $this->command->info("✓ Surveillant Général créé: {$surveillant->email}");

        // Opérateur Photo 1
        $operateur1 = User::create([
            'nom' => 'YAO',
            'prenoms' => 'Sophie Opérateur',
            'email' => 'operateur@cartescolaire.com',
            'password' => Hash::make('Operateur@123'),
            'role' => 'Operateur Photo',
            'phone' => '+225 07 10 10 10 10',
            'is_active' => true,
        ]);
        $this->command->info("✓ Opérateur Photo créé: {$operateur1->email}");

        // Opérateur Photo 2
        $operateur2 = User::create([
            'nom' => 'TRAORE',
            'prenoms' => 'Ibrahim Opérateur',
            'email' => 'operateur2@cartescolaire.com',
            'password' => Hash::make('Operateur@123'),
            'role' => 'Operateur Photo',
            'phone' => '+225 07 11 11 11 11',
            'is_active' => true,
        ]);
        $this->command->info("✓ Opérateur Photo 2 créé: {$operateur2->email}");
    }

    private function createFilieres(): void
    {
        $this->command->info('Création des filières...');
        
        $filieres = [
            ['nom' => 'Série A', 'code' => 'A', 'niveau' => 'Lycee', 'description' => 'Littérature, Philosophie, Langues'],
            ['nom' => 'Série C', 'code' => 'C', 'niveau' => 'Lycee', 'description' => 'Mathématiques, Physique, Chimie'],
            ['nom' => 'Série D', 'code' => 'D', 'niveau' => 'Lycee', 'description' => 'Sciences Naturelles, SVT'],
            ['nom' => 'Série E', 'code' => 'E', 'niveau' => 'Lycee', 'description' => 'Économie, Gestion'],
        ];

        foreach ($filieres as $filiere) {
            Filiere::create($filiere);
            $this->command->info("✓ Filière créée: {$filiere['nom']}");
        }
    }

    private function createEtablissements(): array
    {
        $this->command->info('Création des établissements...');
        
        $etablissements = [
            [
                'nom' => 'Lycée Moderne de Cocody',
                'localisation' => 'Cocody II Plateaux',
                'ville' => 'Abidjan',
                'commune' => 'Cocody',
                'type' => 'Lycee',
                'grade' => 'Public',
                'code_etablissement' => 'LMC2025',
                'telephone' => '+225 27 22 44 55 66',
                'email' => 'contact@lmcocody.ci',
                'adresse' => 'Boulevard Latrille, Cocody II Plateaux',
            ],
            [
                'nom' => 'Collège Catholique Sainte-Marie',
                'localisation' => 'Marcory Zone 4',
                'ville' => 'Abidjan',
                'commune' => 'Marcory',
                'type' => 'College',
                'grade' => 'Prive',
                'code_etablissement' => 'CCSM2025',
                'telephone' => '+225 27 21 35 67 89',
                'email' => 'contact@saintemarie.ci',
                'adresse' => 'Rue des Jardins, Marcory Zone 4',
            ],
            [
                'nom' => 'Groupe Scolaire Les Pionniers',
                'localisation' => 'Yopougon Niangon',
                'ville' => 'Abidjan',
                'commune' => 'Yopougon',
                'type' => 'Mixte',
                'grade' => 'Prive',
                'code_etablissement' => 'GSLP2025',
                'telephone' => '+225 07 78 90 12 34',
                'email' => 'info@lespionniers.ci',
                'adresse' => 'Carrefour Niangon Nord',
            ],
        ];

        $createdEtablissements = [];
        foreach ($etablissements as $data) {
            $etablissement = Etablissement::create($data);
            $createdEtablissements[] = $etablissement;
            $this->command->info("✓ Établissement créé: {$etablissement->nom}");
        }

        return $createdEtablissements;
    }

    private function createClasses(Etablissement $etablissement): void
    {
        $this->command->info("Création des classes pour {$etablissement->nom}...");
        
        $anneeScolaire = '2024-2025';
        
        if ($etablissement->type === 'Lycee' || $etablissement->type === 'Mixte') {
            // Classes de Lycée
            $niveauxLycee = ['Seconde', 'Premiere', 'Terminale'];
            $filieres = Filiere::all();
            
            foreach ($niveauxLycee as $niveau) {
                foreach ($filieres as $filiere) {
                    for ($i = 1; $i <= 2; $i++) {
                        Classe::create([
                            'nom' => "{$niveau} {$filiere->code}{$i}",
                            'niveau' => $niveau,
                            'etablissement_id' => $etablissement->id,
                            'filiere_id' => $filiere->id,
                            'salle' => "Salle " . rand(1, 30),
                            'effectif_max' => 60,
                            'effectif_actuel' => rand(30, 55),
                            'annee_scolaire' => $anneeScolaire,
                            'is_active' => true,
                        ]);
                    }
                }
            }
        }
        
        if ($etablissement->type === 'College' || $etablissement->type === 'Mixte') {
            // Classes de Collège
            $niveauxCollege = ['6eme', '5eme', '4eme', '3eme'];
            
            foreach ($niveauxCollege as $niveau) {
                for ($i = 1; $i <= 3; $i++) {
                    Classe::create([
                        'nom' => ucfirst($niveau) . " {$i}",
                        'niveau' => $niveau,
                        'etablissement_id' => $etablissement->id,
                        'salle' => "Salle " . rand(1, 30),
                        'effectif_max' => 50,
                        'effectif_actuel' => rand(25, 45),
                        'annee_scolaire' => $anneeScolaire,
                        'is_active' => true,
                    ]);
                }
            }
        }
        
        if ($etablissement->type === 'Primaire' || $etablissement->type === 'Mixte') {
            // Classes de Primaire
            $niveauxPrimaire = ['CP1', 'CP2', 'CE1', 'CE2', 'CM1', 'CM2'];
            
            foreach ($niveauxPrimaire as $niveau) {
                for ($i = 1; $i <= 2; $i++) {
                    Classe::create([
                        'nom' => "{$niveau} {$i}",
                        'niveau' => $niveau,
                        'etablissement_id' => $etablissement->id,
                        'salle' => "Salle " . rand(1, 20),
                        'effectif_max' => 40,
                        'effectif_actuel' => rand(20, 35),
                        'annee_scolaire' => $anneeScolaire,
                        'is_active' => true,
                    ]);
                }
            }
        }
        
        $this->command->info("✓ Classes créées pour {$etablissement->nom}");
    }

    private function createEleves(): void
    {
        $this->command->info('Création d\'élèves de test...');
        
        $classes = Classe::all();
        $anneeScolaire = '2024-2025';
        
        $noms = ['KOUAME', 'KOFFI', 'YAO', 'BAMBA', 'TOURE', 'DIALLO', 'KONE', 'OUATTARA', 'TRAORE', 'COULIBALY'];
        $prenoms = ['Aya', 'Kouadio', 'Amenan', 'Yao', 'N\'Guessan', 'Adjoua', 'Kouakou', 'Affoue', 'Akissi', 'Desire'];
        $villes = ['Abidjan', 'Bouaké', 'Yamoussoukro', 'San-Pedro', 'Daloa', 'Korhogo', 'Man'];
        
        $compteur = 1;
        
        foreach ($classes as $classe) {
            // Créer 5 élèves par classe
            for ($i = 0; $i < 5; $i++) {
                $nom = $noms[array_rand($noms)];
                $prenom = $prenoms[array_rand($prenoms)];
                $sexe = rand(0, 1) ? 'M' : 'F';
                
                Eleve::create([
                    'matricule' => 'E' . str_pad($compteur, 6, '0', STR_PAD_LEFT),
                    'nom' => $nom,
                    'prenoms' => $prenom,
                    'date_naissance' => now()->subYears(rand(6, 18))->subDays(rand(1, 365)),
                    'lieu_naissance' => $villes[array_rand($villes)],
                    'sexe' => $sexe,
                    'nationalite' => 'Ivoirienne',
                    'contact_parent' => '+225 07 ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99) . ' ' . rand(10, 99),
                    'nom_parent' => $noms[array_rand($noms)] . ' ' . $prenoms[array_rand($prenoms)],
                    'etablissement_id' => $classe->etablissement_id,
                    'classe_id' => $classe->id,
                    'annee_scolaire' => $anneeScolaire,
                    'statut' => 'Actif',
                    'date_inscription' => now()->subMonths(rand(1, 6)),
                    'redoublant' => rand(0, 10) > 8, // 20% de redoublants
                ]);
                
                $compteur++;
            }
        }
        
        $this->command->info("✓ {$compteur} élèves créés");
    }
}
