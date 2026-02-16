<?php

namespace App\Services;

use App\Models\CarteScolaire;
use App\Models\Eleve;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class CarteService
{
    /**
     * Générer une carte scolaire pour un élève
     */
    public function genererCarte(Eleve $eleve, array $options = []): CarteScolaire
    {
        // Vérifier que l'élève a une photo approuvée
        $photo = $eleve->photoApprouvee;
        if (!$photo) {
            throw new \Exception("L'élève n'a pas de photo approuvée.");
        }

        // Générer un numéro unique de carte
        $numeroCarte = $this->genererNumeroCarte($eleve);

        // Créer les données de la carte
        $donneesQRCode = $this->creerDonneesQRCode($eleve, $numeroCarte);
        
        // Générer le QR Code
        $cheminQRCode = $this->genererQRCode($donneesQRCode, $numeroCarte);

        // Créer l'enregistrement de la carte
        $carte = CarteScolaire::create([
            'numero_carte' => $numeroCarte,
            'eleve_id' => $eleve->id,
            'photo_id' => $photo->id,
            'etablissement_id' => $eleve->etablissement_id,
            'classe_id' => $eleve->classe_id,
            'generateur_id' => auth()->id(),
            'date_generation' => now(),
            'annee_scolaire' => $eleve->annee_scolaire,
            'qr_code_data' => json_encode($donneesQRCode),
            'chemin_qr_code' => $cheminQRCode,
            'code_barres' => $this->genererCodeBarres($numeroCarte),
            'statut' => 'Carte_generee',
            'modele_carte' => $options['modele'] ?? 'standard',
            'donnees_carte' => $this->preparerDonneesCarte($eleve, $photo),
            'date_debut_validite' => now()->startOfYear(),
            'date_fin_validite' => now()->endOfYear(),
            'est_valide' => true,
        ]);

        // Générer les visuels de la carte (recto/verso)
        $this->genererVisuels($carte);

        // Logger l'action
        $this->logAction('create', $carte, "Carte générée pour {$eleve->nom_complet}");

        return $carte;
    }

    /**
     * Générer le visuel de la carte (recto et verso)
     */
    private function genererVisuels(CarteScolaire $carte): void
    {
        $eleve = $carte->eleve;
        $etablissement = $carte->etablissement;
        $photo = $carte->photo;

        // Préparer les données pour la vue
        $data = [
            'carte' => $carte,
            'eleve' => $eleve,
            'etablissement' => $etablissement,
            'photo' => $photo,
            'classe' => $carte->classe,
        ];

        // Générer le PDF avec les deux faces
        $pdf = Pdf::loadView('cartes.templates.standard', $data);
        $pdf->setPaper([0, 0, 242.65, 153.07], 'portrait'); // Format ID-1 (85.6mm x 53.98mm)

        // Sauvegarder le PDF
        $nomFichier = "carte_{$carte->numero_carte}.pdf";
        $cheminPdf = "cartes/{$eleve->annee_scolaire}/{$nomFichier}";
        Storage::disk('public')->put($cheminPdf, $pdf->output());

        // Mettre à jour la carte avec les chemins
        $carte->update([
            'chemin_pdf' => $cheminPdf,
            'chemin_recto' => $cheminPdf, // Même fichier pour l'instant
            'chemin_verso' => $cheminPdf,
        ]);
    }

    /**
     * Générer un numéro unique de carte
     */
    private function genererNumeroCarte(Eleve $eleve): string
    {
        $annee = date('Y');
        $etablissement = substr($eleve->etablissement->code_etablissement, 0, 4);
        $sequence = str_pad(CarteScolaire::count() + 1, 6, '0', STR_PAD_LEFT);
        
        return "{$annee}{$etablissement}{$sequence}";
    }

    /**
     * Créer les données pour le QR Code
     */
    private function creerDonneesQRCode(Eleve $eleve, string $numeroCarte): array
    {
        return [
            'numero_carte' => $numeroCarte,
            'matricule' => $eleve->matricule,
            'nom' => $eleve->nom,
            'prenoms' => $eleve->prenoms,
            'date_naissance' => $eleve->date_naissance->format('d/m/Y'),
            'etablissement' => $eleve->etablissement->nom,
            'classe' => $eleve->classe->nom_complet,
            'annee_scolaire' => $eleve->annee_scolaire,
        ];
    }

    /**
     * Générer le QR Code
     */
    private function genererQRCode(array $donnees, string $numeroCarte): string
    {
        $qrCodeContent = json_encode($donnees);
        
        $qrCode = QrCode::format('png')
            ->size(200)
            ->margin(1)
            ->generate($qrCodeContent);
        
        $nomFichier = "qr_code_{$numeroCarte}.png";
        $chemin = "qrcodes/{$nomFichier}";
        
        Storage::disk('public')->put($chemin, $qrCode);
        
        return $chemin;
    }

    /**
     * Générer un code-barres
     */
    private function genererCodeBarres(string $numeroCarte): string
    {
        // Utiliser une bibliothèque de code-barres comme picqer/php-barcode-generator
        // Pour l'instant, on retourne simplement le numéro
        return $numeroCarte;
    }

    /**
     * Préparer toutes les données de la carte
     */
    private function preparerDonneesCarte(Eleve $eleve, Photo $photo): array
    {
        return [
            'eleve' => [
                'nom' => $eleve->nom,
                'prenoms' => $eleve->prenoms,
                'matricule' => $eleve->matricule,
                'date_naissance' => $eleve->date_naissance->format('d/m/Y'),
                'lieu_naissance' => $eleve->lieu_naissance,
                'sexe' => $eleve->sexe,
            ],
            'etablissement' => [
                'nom' => $eleve->etablissement->nom,
                'localisation' => $eleve->etablissement->localisation,
                'telephone' => $eleve->etablissement->telephone,
                'logo' => $eleve->etablissement->logo_url,
            ],
            'classe' => [
                'nom' => $eleve->classe->nom_complet,
                'niveau' => $eleve->classe->niveau,
            ],
            'photo' => [
                'url' => $photo->photo_redimensionnee_url,
            ],
        ];
    }

    /**
     * Imprimer une carte
     */
    public function imprimerCarte(CarteScolaire $carte): void
    {
        $carte->update([
            'statut' => 'Carte_imprimee',
            'date_impression' => now(),
            'imprimeur_id' => auth()->id(),
        ]);

        $carte->incrementImpressions();

        $this->logAction('print', $carte, "Carte imprimée");
    }

    /**
     * Imprimer plusieurs cartes en masse
     */
    public function imprimerEnMasse(array $carteIds): array
    {
        $cartes = CarteScolaire::whereIn('id', $carteIds)
            ->where('statut', 'Carte_generee')
            ->get();

        $resultats = [
            'succes' => 0,
            'echecs' => 0,
            'messages' => []
        ];

        foreach ($cartes as $carte) {
            try {
                $this->imprimerCarte($carte);
                $resultats['succes']++;
            } catch (\Exception $e) {
                $resultats['echecs']++;
                $resultats['messages'][] = "Erreur pour la carte {$carte->numero_carte}: {$e->getMessage()}";
            }
        }

        return $resultats;
    }

    /**
     * Marquer une carte comme distribuée
     */
    public function marquerCommeDistribuee(CarteScolaire $carte): void
    {
        $carte->update([
            'statut' => 'Carte_distribuee',
            'date_distribution' => now(),
            'distributeur_id' => auth()->id(),
        ]);

        $this->logAction('distribute', $carte, "Carte distribuée");
    }

    /**
     * Générer un duplicata
     */
    public function genererDuplicata(CarteScolaire $carteOriginale): CarteScolaire
    {
        $nouvelleCarte = $this->genererCarte(
            $carteOriginale->eleve,
            ['modele' => $carteOriginale->modele_carte]
        );

        $nouvelleCarte->update([
            'is_duplicate' => true,
            'carte_originale_id' => $carteOriginale->id,
        ]);

        $this->logAction('duplicate', $nouvelleCarte, "Duplicata généré pour la carte {$carteOriginale->numero_carte}");

        return $nouvelleCarte;
    }

    /**
     * Logger une action
     */
    private function logAction(string $type, CarteScolaire $carte, string $description): void
    {
        \App\Models\Action::create([
            'user_id' => auth()->id(),
            'type_action' => $type,
            'module' => 'CarteScolaire',
            'description' => $description,
            'entite_type' => 'CarteScolaire',
            'entite_id' => $carte->id,
            'adresse_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'statut' => 'Succes',
        ]);
    }
}
