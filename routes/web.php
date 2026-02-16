<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\CarteController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\EtablissementController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SurveillantController;
use App\Http\Controllers\SurveillantPhotoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Routes publiques
Route::get('/', function () {
    return redirect()->route('login');
});

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// Routes authentifiées
Route::middleware('auth')->group(function () {
    
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes Administrateur
    Route::middleware('role:Administrateur')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des utilisateurs
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Gestion des établissements
        Route::resource('etablissements', EtablissementController::class);
        Route::post('etablissements/{etablissement}/toggle-status', [EtablissementController::class, 'toggleStatus'])->name('etablissements.toggle-status');
        
        // Rapports et statistiques
        Route::get('/rapports', [ReportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/global', [ReportController::class, 'global'])->name('rapports.global');
        Route::get('/rapports/export-excel', [ReportController::class, 'exportExcel'])->name('rapports.export-excel');
        Route::get('/rapports/export-pdf', [ReportController::class, 'exportPdf'])->name('rapports.export-pdf');
        
        // Logs d'audit
        Route::get('/audit', [ReportController::class, 'audit'])->name('audit.index');
    });
    
    // Routes Proviseur
    Route::middleware('role:Proviseur,Administrateur')->prefix('proviseur')->name('proviseur.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Gestion des classes
        Route::resource('classes', ClasseController::class);
        Route::post('classes/{classe}/import-eleves', [ClasseController::class, 'importEleves'])->name('classes.import-eleves');
        
        // Gestion des élèves
        Route::resource('eleves', EleveController::class);
        Route::get('eleves/export/excel', [EleveController::class, 'exportExcel'])->name('eleves.export-excel');
        Route::get('eleves/export/pdf', [EleveController::class, 'exportPdf'])->name('eleves.export-pdf');
        Route::post('eleves/import', [EleveController::class, 'import'])->name('eleves.import');
        Route::get('eleves/template/download', [EleveController::class, 'downloadTemplate'])->name('eleves.template');
        
        // Validation des données
        Route::post('eleves/{eleve}/validate', [EleveController::class, 'validate'])->name('eleves.validate');
        
        // Statistiques
        Route::get('/statistiques', [ReportController::class, 'proviseur'])->name('statistiques');
    });
    
    // Routes Surveillant Général
    Route::get('/surveillant/impression/batch',[CarteController::class, 'impressionBatch'])->name('surveillant.impression.batch');

        
        // Validation des photos
        Route::get('/photos/validation', [PhotoController::class, 'validationIndex'])->name('photos.validation');
        Route::post('/photos/{photo}/approve', [PhotoController::class, 'approve'])->name('photos.approve');
        Route::post('/photos/{photo}/reject', [PhotoController::class, 'reject'])->name('photos.reject');
        Route::post('/photos/bulk-approve', [PhotoController::class, 'bulkApprove'])->name('photos.bulk-approve');
        
        // Gestion des cartes
        Route::get('/cartes', [CarteController::class, 'index'])->name('cartes.index');
        Route::get('/cartes/{carte}', [CarteController::class, 'show'])->name('cartes.show');
        Route::post('/cartes/bulk-print', [CarteController::class, 'bulkPrint'])->name('cartes.bulk-print');
        Route::post('/cartes/{carte}/mark-as-distributed', [CarteController::class, 'markAsDistributed'])->name('cartes.distribute');
        
        // Impression
        Route::get('/impression', [CarteController::class, 'impressionIndex'])->name('impression.index');
        Route::post('/impression/process', [CarteController::class, 'processImpression'])->name('impression.process');
        
        // Rapports
        Route::get('/rapports/classe/{classe}', [ReportController::class, 'classe'])->name('rapports.classe');
    });
    
    // Routes Opérateur Photo
    Route::middleware('role:Operateur Photo,Surveillant General,Proviseur,Administrateur')->prefix('operateur')->name('operateur.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Prise de photos
        Route::get('/photo/capture', [PhotoController::class, 'capture'])->name('photo.capture');
        Route::post('/photo/upload', [PhotoController::class, 'upload'])->name('photo.upload');
        Route::get('/photo/eleve/{eleve}', [PhotoController::class, 'captureForEleve'])->name('photo.eleve');
        Route::post('/photo/save', [PhotoController::class, 'save'])->name('photo.save');
        Route::post('/photo/{photo}/retake', [PhotoController::class, 'retake'])->name('photo.retake');
        
        // Galerie photos
        Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index');
        Route::get('/photos/{photo}', [PhotoController::class, 'show'])->name('photos.show');
        Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');
        
        // Génération de cartes
        Route::post('/cartes/generate/{eleve}', [CarteController::class, 'generate'])->name('cartes.generate');
        Route::post('/cartes/regenerate/{carte}', [CarteController::class, 'regenerate'])->name('cartes.regenerate');
    });
    
    // Routes communes à tous les utilisateurs authentifiés
    Route::middleware('auth')->group(function () {
        
        // Recherche d'élèves
        Route::get('/eleves/search', [EleveController::class, 'search'])->name('eleves.search');
        Route::get('/eleves/{eleve}/details', [EleveController::class, 'show'])->name('eleves.show');
        
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
        Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        
        // Profil utilisateur
        Route::get('/profile', [UserController::class, 'profile'])->name('profile');
        Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password');
        
        // Aide et documentation
        Route::get('/aide', function () {
            return view('aide.index');
        })->name('aide');
        
        // Téléchargements
        Route::get('/download/carte/{carte}', [CarteController::class, 'download'])->name('cartes.download');
        Route::get('/download/photo/{photo}', [PhotoController::class, 'download'])->name('photos.download');
    });

// Routes API (si nécessaire)
Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    Route::get('/eleves/{eleve}/photo', [PhotoController::class, 'getElevePhoto']);
    Route::get('/classes/{classe}/eleves', [ClasseController::class, 'getEleves']);
    Route::get('/stats/dashboard', [DashboardController::class, 'getStats']);
});


// Route dashboard général
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.dashboard');



// routes/web.php

Route::prefix('operateur')->name('operateur.')->group(function () {
    Route::get('eleves', [App\Http\Controllers\EleveController::class, 'index'])->name('eleves.index');
});





Route::get('surveillant/impression', [SurveillantController::class, 'impression'])->name('surveillant.impression');


Route::get('surveillant/photos/validation', [SurveillantPhotoController::class, 'validation'])->name('surveillant.photos.validation');


// Dans routes/web.php

Route::prefix('surveillant')->name('surveillant.')->group(function () {
    // Liste des cartes : URL = /surveillant/cartes | Nom = surveillant.cartes.index
    Route::get('cartes', [CarteController::class, 'index'])->name('cartes.index');
    
    // Détails d'une carte : URL = /surveillant/cartes/{carte} | Nom = surveillant.cartes.show
    Route::get('cartes/{carte}', [CarteController::class, 'show'])->name('cartes.show');
    
    // Validation des photos (on le déplace ici pour la cohérence)
    Route::get('photos/validation', [SurveillantPhotoController::class, 'validation'])->name('photos.validation');
});

Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

// Dans routes/web.php

Route::prefix('surveillant')->name('surveillant.dashboard')->group(function () {
    
    // AJOUTEZ CETTE LIGNE (La route manquante)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('cartes', [CarteController::class, 'index'])->name('cartes.index');
    Route::get('cartes/{carte}', [CarteController::class, 'show'])->name('cartes.show');
    // ... reste de vos routes
});