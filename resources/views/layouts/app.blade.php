<!doctype html>
<html lang="it">
    <head>
         <?php
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: *');
            header('Access-Control-Allow-Headers: *');
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IUCN Red List Explorer - Dashboard</title>
        <script src="https://cdn.tailwindcss.com/3.4.17"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&amp;display=swap" rel="stylesheet">
        <style>
            * { font-family: 'Nunito', sans-serif; }
            html, body { height: 100%; margin: 0; }
            .app-wrapper { min-height: 100%; display: flex; flex-direction: column; }

            .loader {
                border: 3px solid #e5e7eb;
                border-top: 3px solid #059669;
                border-radius: 50%;
                width: 24px;
                height: 24px;
                animation: spin 1s linear infinite;
            }
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }

            .fade-in { animation: fadeIn 0.3s ease-in; }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .system-card {
                transition: all 0.3s ease;
                cursor: pointer;
            }
            .system-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            }

            .country-badge {
                transition: all 0.2s ease;
            }
            .country-badge:hover {
                transform: scale(1.05);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            }
        </style>
        <style>body { box-sizing: border-box; }</style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flag-icons/css/flag-icons.min.css">
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    </head>
    <body class="h-full bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50">
        <div class="app-wrapper w-full"><!-- Header -->
            <header class="bg-gradient-to-r from-emerald-700 via-teal-600 to-cyan-700 text-white shadow-lg">
                <div class="max-w-7xl mx-auto px-4 py-6">
                <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center"><span class="text-2xl">🌿</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">IUCN Red List Explorer</h1>
                    <p class="text-emerald-100 text-sm">Database Globale Conservazione Specie</p>
                </div>
                </div>
                <nav class="flex gap-2">
                    <a href="#" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-sm font-semibold"> 🏠 Dashboard </a>
                    <a href="#" class="px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition text-sm font-semibold"> ⭐ Preferiti </a>
                </nav>
                </div>
                </div>
            </header>

        @yield('content')
        @include('layouts.footer')
        </div>
    </body>
</html>
