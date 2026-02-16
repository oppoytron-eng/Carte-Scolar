<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Carte Scolaire') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #7C3AED;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #3B82F6;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F3F4F6;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #4F46E5 0%, #7C3AED 100%);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 4px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #4338CA;
            border-color: #4338CA;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .stat-card {
            border-left: 4px solid var(--primary-color);
        }

        .table-hover tbody tr:hover {
            background-color: #F9FAFB;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.7rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 col-lg-2 d-md-block sidebar px-0">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">
                            <i class="fas fa-id-card"></i>
                            CarteSchool
                        </h4>
                        <small class="text-white-50">{{ Auth::user()->role }}</small>
                    </div>

                    <ul class="nav flex-column px-2">
                        <li class="nav-item">
                            @php
                                $roleRouteMap = [
                                    'Administrateur' => 'admin',
                                    'Proviseur' => 'proviseur',
                                    'Surveillant_General' => 'surveillant',
                                    'Operateur_Photo' => 'operateur',
                                ];
                                $routePrefix = $roleRouteMap[Auth::user()->role] ?? 'dashboard';
                            @endphp
                            <a class="nav-link {{ request()->routeIs('*.dashboard') ? 'active' : '' }}" href="{{ route($routePrefix . '.dashboard') }}">
                                <i class="fas fa-home me-2"></i> Tableau de bord
                            </a>
                        </li>

                        @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-users me-2"></i> Utilisateurs
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.etablissements.*') ? 'active' : '' }}" href="{{ route('admin.etablissements.index') }}">
                                    <i class="fas fa-school me-2"></i> Établissements
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.rapports.*') ? 'active' : '' }}" href="{{ route('admin.rapports.index') }}">
                                    <i class="fas fa-chart-bar me-2"></i> Rapports
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->isProviseur() || Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('proviseur.classes.*') ? 'active' : '' }}" href="{{ route('proviseur.classes.index') }}">
                                    <i class="fas fa-chalkboard me-2"></i> Classes
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('proviseur.eleves.*') ? 'active' : '' }}" href="{{ route('proviseur.eleves.index') }}">
                                    <i class="fas fa-user-graduate me-2"></i> Élèves
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->isSurveillant() || Auth::user()->isProviseur() || Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('surveillant.photos.*') ? 'active' : '' }}"
                                 href="{{ route('surveillant.photos.validation') }}"
                                 >
                                    <i class="fas fa-camera me-2"></i> Validation Photos
                                </a>
                            </li>
                            <li class="nav-item">
                                <li class="nav-item">
    <a class="nav-link {{ request()->is('surveillant/cartes*') ? 'active' : '' }}" href="{{ url('/surveillant/cartes') }}">
        <i class="fas fa-id-card me-2"></i> Cartes
    </a>
</li>
<li class="nav-item">
    <a class="nav-link {{ request()->is('surveillant/impression*') ? 'active' : '' }}" href="{{ url('/surveillant/impression') }}">
        <i class="fas fa-print me-2"></i> Impression
    </a>
</li>
                    
                        @endif

                        @if(Auth::user()->isOperateur())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('operateur.photo.*') ? 'active' : '' }}" href="{{ route('operateur.photo.capture') }}">
                                    <i class="fas fa-camera me-2"></i> Prise de Photo
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('operateur.photos.*') ? 'active' : '' }}" href="{{ route('operateur.photos.index') }}">
                                    <i class="fas fa-images me-2"></i> Mes Photos
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" href="{{ route('notifications.index') }}">
                                <i class="fas fa-bell me-2"></i> Notifications
                                @if(Auth::user()->notificationsNonLues->count() > 0)
                                    <span class="notification-badge">{{ Auth::user()->notificationsNonLues->count() }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="nav-item mt-4">
                            <a class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" href="{{ route('profile') }}">
                                <i class="fas fa-user-cog me-2"></i> Profil
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('aide') }}">
                                <i class="fas fa-question-circle me-2"></i> Aide
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-start w-100">
                                    <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto col-lg-10 px-md-4">
                <!-- Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light my-3">
                    <div class="container-fluid">
                        <h5 class="mb-0">@yield('page-title', 'Tableau de bord')</h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3">{{ Auth::user()->full_name }}</span>
                            <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="rounded-circle" width="40" height="40">
                        </div>
                    </div>
                </nav>

                <!-- Alerts -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Page Content -->
                <div class="py-3">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // CSRF Token for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
