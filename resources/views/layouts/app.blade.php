<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Penjemput</title>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Face Detection API -->
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/face-detection"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css">
    @yield('style')
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark position-fixed h-100">
            <div class="sidebar-header">
                <h3 class="text-white">Aplikasi Penjemput</h3>
            </div>

            <ul class="list-unstyled components">
                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="{{ Request::is('FaceDetection') ? 'active' : '' }}">
                    <a href="{{ route('FaceDetection') }}">
                        <i class="bi bi-camera"></i> Absensi
                    </a>
                </li>
                <li class="{{ Request::is('admin/students*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}">
                        <i class="bi bi-people"></i> Siswa
                    </a>
                </li>
                <li class="{{ Request::is('admin/classes*') ? 'active' : '' }}">
                    <a href="{{ route('classes.index') }}">
                        <i class="bi bi-building"></i> Kelas
                    </a>
                </li>
                <li class="{{ Request::is('admin/schools*') ? 'active' : '' }}">
                    <a href="{{ route('schools.index') }}">
                        <i class="bi bi-bank"></i> Sekolah
                    </a>
                </li>
                <li class="{{ Request::is('admin/pickups*') ? 'active' : '' }}">
                    <a href="{{ route('pickups.index') }}">
                        <i class="bi bi-person-badge"></i> Penjemput
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="d-flex flex-column min-vh-100">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light position-sticky top-0 shadow-sm" style="z-index: 1000;">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="dropdown ms-auto">
                        <span class="me-3 text-dark fw-bold">{{ Auth::user()->name }}</span>
                        <img src="{{ asset('img/profil.jpg') }}" alt="Profile" class="rounded-circle" width="40">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" 
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid flex-grow-1 py-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container-fluid">
            <p class="mb-0">2025 © Create BY Kelompok PKL SMKN 65 JAKARTA</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        body {
            overflow-x: hidden;
        }

        .wrapper {
            display: flex;
            width: 100%;
        }

        #sidebar {
            min-width: 250px;
            max-width: 250px;
            z-index: 1001;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        #sidebar .sidebar-header {
            padding: 20px;
            background: #343a40;
        }

        #sidebar ul li a {
            padding: 10px;
            font-size: 1.1em;
            display: block;
            color: white;
            text-decoration: none;
        }

        #sidebar ul li a:hover {
            background: #6c757d;
        }

        #sidebar ul li.active > a {
            background: #6c757d;
        }

        #content {
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.3s;
            padding-bottom: 60px;
        }

        #sidebar.active + #content {
            margin-left: 0;
            width: 100%;
        }

        footer {
            position: relative
            width: 100%;
            z-index: 1000;
            background-color: #d6d6d6;
            color: #333;
            padding: 0.5rem 0;
            font-size: 0.875rem;
            margin-top: auto;
            bottom: 0;
            left: 0;
            text-align: center;
        }

        .navbar {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content {
                margin-left: 0;
                width: 100%;
            }
            
            #sidebar.active + #content {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }
    </style>

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
@yield('script')

    <!-- Custom JS -->
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>