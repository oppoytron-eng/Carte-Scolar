<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Carte Scolaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }

        .login-left {
            background: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            padding: 60px 40px;
        }

        .form-control:focus {
            border-color: #4F46E5;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .btn-primary {
            background-color: #4F46E5;
            border: none;
            padding: 12px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #4338CA;
        }

        .logo-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Left Side - Branding -->
                <div class="col-md-5 login-left">
                    <div class="logo-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Carte Scolaire</h2>
                    <p class="mb-4">Système de gestion de cartes scolaires moderne et efficace</p>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-camera"></i>
                        </div>
                        <div>
                            <strong>Prise de photo</strong>
                            <p class="mb-0 small">Capture et validation rapide</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <div>
                            <strong>QR Codes</strong>
                            <p class="mb-0 small">Génération automatique</p>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div>
                            <strong>Impression</strong>
                            <p class="mb-0 small">Impression en masse</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Login Form -->
                <div class="col-md-7 login-right">
                    <h3 class="mb-1 fw-bold">Bienvenue !</h3>
                    <p class="text-muted mb-4">Connectez-vous pour continuer</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <input type="email" 
                                       class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="votre@email.com"
                                       required 
                                       autofocus>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <input type="password" 
                                       class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="••••••••"
                                       required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Se connecter
                        </button>

                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                <i class="fas fa-key me-1"></i>
                                Mot de passe oublié ?
                            </a>
                        </div>
                    </form>

                    <!-- Comptes de test -->
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-muted small mb-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Comptes de test disponibles :
                        </p>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Admin:</strong><br>
                                    admin@cartescolaire.com<br>
                                    Admin@123
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Proviseur:</strong><br>
                                    proviseur@cartescolaire.com<br>
                                    Proviseur@123
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Surveillant:</strong><br>
                                    surveillant@cartescolaire.com<br>
                                    Surveillant@123
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Opérateur:</strong><br>
                                    operateur@cartescolaire.com<br>
                                    Operateur@123
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-white">
            <p class="mb-0">© 2025 Carte Scolaire - Tous droits réservés</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
