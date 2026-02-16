<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Action;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Gérer la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Vérifier si l'utilisateur existe et est actif
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            // $this->logAction(null, 'login', 'Auth', 'Tentative de connexion avec email inexistant', 'Echec');
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        if (!$user->is_active) {
            // $this->logAction($user->id, 'login', 'Auth', 'Tentative de connexion avec compte inactif', 'Echec');
            throw ValidationException::withMessages([
                'email' => ['Votre compte a été désactivé. Veuillez contacter l\'administrateur.'],
            ]);
        }

        // Tentative de connexion
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Logger la connexion réussie
            // $this->logAction(Auth::id(), 'login', 'Auth', 'Connexion réussie', 'Succes');

            // Redirection selon le rôle
            return $this->redirectBasedOnRole();
        }

        // Logger l'échec
        // $this->logAction($user->id, 'login', 'Auth', 'Échec de connexion - mot de passe incorrect', 'Echec');

        throw ValidationException::withMessages([
            'email' => ['Les informations d\'identification fournies sont incorrectes.'],
        ]);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        
        // Logger la déconnexion
        $this->logAction($userId, 'logout', 'Auth', 'Déconnexion', 'Succes');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }

    /**
     * Afficher le formulaire d'inscription (si nécessaire)
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Gérer l'inscription (optionnel - peut être restreint aux admins)
     */
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:55',
            'prenoms' => 'required|string|max:55',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'Operateur Photo', // Rôle par défaut
        ]);

        // Logger la création
        $this->logAction($user->id, 'create', 'User', "Création du compte utilisateur", 'Succes');

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès!');
    }

    /**
     * Afficher le formulaire de mot de passe oublié
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Envoyer le lien de réinitialisation
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Logique d'envoi d'email (à implémenter)
        // Password::sendResetLink($request->only('email'));

        return back()->with('success', 'Un lien de réinitialisation a été envoyé à votre adresse email.');
    }

    /**
     * Rediriger selon le rôle de l'utilisateur
     */
    private function redirectBasedOnRole()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'Administrateur':
                return redirect()->route('admin.dashboard')->with('success', "Bienvenue {$user->full_name}!");
            
            case 'Proviseur':
                return redirect()->route('proviseur.dashboard')->with('success', "Bienvenue {$user->full_name}!");
            
            case 'Surveillant General':
                return redirect()->route('surveillant.dashboard')->with('success', "Bienvenue {$user->full_name}!");
            
            case 'Operateur Photo':
                return redirect()->route('operateur.dashboard')->with('success', "Bienvenue {$user->full_name}!");
            
            default:
                return redirect()->route('dashboard')->with('success', "Bienvenue {$user->full_name}!");
        }
    }

    /**
     * Logger les actions d'authentification
     */
    private function logAction(?int $userId, string $type, string $module, string $description, string $statut)
    {
        try {
            Action::create([
                'user_id' => $userId ?? 0, // 0 pour les tentatives sans utilisateur
                'type_action' => $type,
                'module' => $module,
                'description' => $description,
                'adresse_ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'statut' => $statut,
            ]);
        } catch (\Exception $e) {
            // Ignorer les erreurs de logging pour ne pas bloquer l'authentification
            \Log::error('Erreur lors du logging de l\'action: ' . $e->getMessage());
        }
    }
}
