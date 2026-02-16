<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Classe;
use App\Models\Etablissement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ElevesExport;
use App\Imports\ElevesImport;
use Barryvdh\DomPDF\Facade\Pdf;

class EleveController extends Controller
{
    /**
     * Afficher la liste des élèves
     */
    public function index(Request $request)
    {
        $query = Eleve::with(['classe', 'etablissement']);

        // Filtrer par établissement pour les utilisateurs non-admin
        if (!auth()->user()->isAdmin()) {
            $etablissement = auth()->user()->etablissementPrincipal();
            if ($etablissement) {
                $query->where('etablissement_id', $etablissement->id);
            }
        }

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtres
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('sexe')) {
            $query->where('sexe', $request->sexe);
        }

        if ($request->filled('annee_scolaire')) {
            $query->where('annee_scolaire', $request->annee_scolaire);
        }

        $eleves = $query->orderBy('nom')->orderBy('prenoms')->paginate(20);

        // Données pour les filtres
        $classes = Classe::orderBy('nom')->get();
        $etablissements = Etablissement::orderBy('nom')->get();

        return view('proviseur.eleves.index', compact('eleves', 'classes', 'etablissements'));

    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $etablissement = auth()->user()->etablissementPrincipal();
        $classes = $etablissement 
            ? $etablissement->classesActives 
            : Classe::where('is_active', true)->get();

        return view('eleves.create', compact('classes'));
    }

    /**
     * Enregistrer un nouvel élève
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:100',
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'nationalite' => 'required|string|max:50',
            'contact_parent' => 'required|string|max:20',
            'contact_parent_2' => 'nullable|string|max:20',
            'nom_parent' => 'nullable|string|max:150',
            'profession_parent' => 'nullable|string|max:100',
            'adresse_parent' => 'nullable|string',
            'email_parent' => 'nullable|email|max:100',
            'classe_id' => 'required|exists:classes,id',
            'annee_scolaire' => 'required|string|max:20',
            'date_inscription' => 'required|date',
            'observations' => 'nullable|string',
            'redoublant' => 'nullable|boolean',
            'groupe_sanguin' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
        ]);

        $classe = Classe::findOrFail($validated['classe_id']);
        $validated['etablissement_id'] = $classe->etablissement_id;
        $validated['matricule'] = $this->genererMatricule($classe->etablissement_id);
        $validated['statut'] = 'Actif';

        $eleve = Eleve::create($validated);

        // Mettre à jour l'effectif de la classe
        $classe->updateEffectif();

        // Logger l'action
        $this->logAction('create', $eleve, "Création de l'élève {$eleve->nom_complet}");

        return redirect()->route('proviseur.eleves.index')
            ->with('success', "L'élève {$eleve->nom_complet} a été créé avec succès.");
    }

    /**
     * Afficher les détails d'un élève
     */
    public function show(Eleve $eleve)
    {
        $eleve->load(['classe', 'etablissement', 'photos', 'cartesScolaires']);

        return view('eleves.show', compact('eleve'));
    }

    /**
     * Afficher le formulaire de modification
     */
    public function edit(Eleve $eleve)
    {
        $classes = $eleve->etablissement->classesActives;

        return view('eleves.edit', compact('eleve', 'classes'));
    }

    /**
     * Mettre à jour un élève
     */
    public function update(Request $request, Eleve $eleve)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'prenoms' => 'required|string|max:100',
            'date_naissance' => 'required|date|before:today',
            'lieu_naissance' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'nationalite' => 'required|string|max:50',
            'contact_parent' => 'required|string|max:20',
            'contact_parent_2' => 'nullable|string|max:20',
            'nom_parent' => 'nullable|string|max:150',
            'profession_parent' => 'nullable|string|max:100',
            'adresse_parent' => 'nullable|string',
            'email_parent' => 'nullable|email|max:100',
            'classe_id' => 'required|exists:classes,id',
            'statut' => 'required|in:Actif,Inactif,Transfere,Abandonne',
            'observations' => 'nullable|string',
            'redoublant' => 'nullable|boolean',
            'groupe_sanguin' => 'nullable|string|max:5',
            'allergies' => 'nullable|string',
        ]);

        $ancienneClasseId = $eleve->classe_id;
        
        $eleve->update($validated);

        // Si changement de classe, mettre à jour les effectifs
        if ($ancienneClasseId != $validated['classe_id']) {
            Classe::find($ancienneClasseId)?->updateEffectif();
            Classe::find($validated['classe_id'])?->updateEffectif();
        }

        // Logger l'action
        $this->logAction('update', $eleve, "Modification de l'élève {$eleve->nom_complet}");

        return redirect()->route('proviseur.eleves.show', $eleve)
            ->with('success', "L'élève {$eleve->nom_complet} a été modifié avec succès.");
    }

    /**
     * Supprimer un élève
     */
    public function destroy(Eleve $eleve)
    {
        $nom = $eleve->nom_complet;
        $classeId = $eleve->classe_id;
        
        $eleve->delete();

        // Mettre à jour l'effectif de la classe
        Classe::find($classeId)?->updateEffectif();

        // Logger l'action
        $this->logAction('delete', $eleve, "Suppression de l'élève {$nom}");

        return redirect()->route('proviseur.eleves.index')
            ->with('success', "L'élève {$nom} a été supprimé avec succès.");
    }

    /**
     * Exporter la liste des élèves en Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Eleve::with(['classe', 'etablissement']);

        // Appliquer les mêmes filtres que l'index
        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if (!auth()->user()->isAdmin()) {
            $etablissement = auth()->user()->etablissementPrincipal();
            if ($etablissement) {
                $query->where('etablissement_id', $etablissement->id);
            }
        }

        return Excel::download(
            new ElevesExport($query->get()), 
            'eleves_' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Exporter la liste des élèves en PDF
     */
    public function exportPdf(Request $request)
    {
        $query = Eleve::with(['classe', 'etablissement']);

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        if (!auth()->user()->isAdmin()) {
            $etablissement = auth()->user()->etablissementPrincipal();
            if ($etablissement) {
                $query->where('etablissement_id', $etablissement->id);
            }
        }

        $eleves = $query->orderBy('nom')->orderBy('prenoms')->get();

        $pdf = Pdf::loadView('eleves.exports.pdf', compact('eleves'));
        
        return $pdf->download('eleves_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Importer des élèves depuis Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
            'classe_id' => 'required|exists:classes,id',
        ]);

        try {
            $classe = Classe::findOrFail($request->classe_id);
            
            Excel::import(new ElevesImport($classe), $request->file('file'));

            // Mettre à jour l'effectif
            $classe->updateEffectif();

            return redirect()->route('proviseur.eleves.index')
                ->with('success', 'Les élèves ont été importés avec succès.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', "Erreur lors de l'importation : " . $e->getMessage());
        }
    }

    /**
     * Télécharger le template Excel pour l'import
     */
    public function downloadTemplate()
    {
        $filePath = resource_path('templates/import_eleves_template.xlsx');
        
        if (!file_exists($filePath)) {
            // Créer un template simple
            return $this->createTemplate();
        }

        return response()->download($filePath);
    }

    /**
     * Créer un template d'import
     */
    private function createTemplate()
    {
        $headers = [
            'nom',
            'prenoms',
            'date_naissance',
            'lieu_naissance',
            'sexe',
            'contact_parent',
            'nom_parent',
            'adresse_parent'
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // En-têtes
        foreach ($headers as $index => $header) {
            $sheet->setCellValueByColumnAndRow($index + 1, 1, ucfirst(str_replace('_', ' ', $header)));
        }

        // Exemple de données
        $sheet->setCellValue('A2', 'KOUAME');
        $sheet->setCellValue('B2', 'Yao');
        $sheet->setCellValue('C2', '2010-05-15');
        $sheet->setCellValue('D2', 'Abidjan');
        $sheet->setCellValue('E2', 'M');
        $sheet->setCellValue('F2', '+225 07 00 00 00 00');
        $sheet->setCellValue('G2', 'KOUAME Koffi');
        $sheet->setCellValue('H2', 'Cocody, Abidjan');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'template_import_eleves.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Valider les données d'un élève
     */
    public function validate(Eleve $eleve)
    {
        // Logique de validation spécifique
        // Par exemple, vérifier que toutes les informations sont complètes

        $this->logAction('validate', $eleve, "Validation des données de l'élève {$eleve->nom_complet}");

        return redirect()->back()->with('success', 'Données validées avec succès.');
    }

    /**
     * Recherche d'élèves (AJAX)
     */
    public function search(Request $request)
    {
        $query = Eleve::query();

        if ($request->filled('q')) {
            $query->search($request->q);
        }

        if ($request->filled('classe_id')) {
            $query->where('classe_id', $request->classe_id);
        }

        $eleves = $query->limit(10)->get(['id', 'nom', 'prenoms', 'matricule', 'classe_id']);

        return response()->json($eleves);
    }

    /**
     * Générer un matricule unique
     */
    private function genererMatricule(int $etablissementId): string
    {
        $etablissement = Etablissement::find($etablissementId);
        $annee = date('Y');
        $code = substr($etablissement->code_etablissement, 0, 3);
        $sequence = str_pad(Eleve::where('etablissement_id', $etablissementId)->count() + 1, 5, '0', STR_PAD_LEFT);
        
        return "{$annee}{$code}{$sequence}";
    }

    /**
     * Logger une action
     */
    private function logAction(string $type, Eleve $eleve, string $description)
    {
        \App\Models\Action::create([
            'user_id' => auth()->id(),
            'type_action' => $type,
            'module' => 'Eleve',
            'description' => $description,
            'entite_type' => 'Eleve',
            'entite_id' => $eleve->id,
            'adresse_ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'statut' => 'Succes',
        ]);
    }
}
