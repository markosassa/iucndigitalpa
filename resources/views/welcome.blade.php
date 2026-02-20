<!doctype html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IUCN Red List Explorer - Dashboard</title>
        <script src="https://cdn.tailwindcss.com/3.4.17"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        <script src="/_sdk/data_sdk.js" type="text/javascript"></script>
        <script src="/_sdk/element_sdk.js" type="text/javascript"></script>
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

            <!-- Main Content -->
            <main class="flex-1 max-w-7xl mx-auto px-4 py-8 w-full"><!-- Welcome Section -->
                <section class="mb-12 fade-in">
                <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-2">Benvenuto nella Dashboard IUCN</h2>
                <p class="text-gray-600">Esplora i dati sulla conservazione delle specie dai Sistemi Ecologici o seleziona una nazione</p>
                </div>
                </section>

                <!-- Sistemi Ecologici Section -->
                <section class="mb-12">
                    <div class="flex items-center gap-3 mb-6"><span class="text-3xl">🌐</span>
                        <h2 class="text-2xl font-bold text-gray-800">Sistemi Ecologici</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                        <a href="#" class="system-card group">
                            <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-8 rounded-xl shadow-lg">
                                <div class="text-5xl mb-4">
                                icona
                                </div>
                                <h3 class="text-2xl font-bold mb-2">Nome sistema</h3>
                                <p class="text-green-100 text-sm mb-4">descrizione sistema</p>
                                <div class="flex items-center gap-2 text-green-200 group-hover:translate-x-2 transition-transform">
                                    <span class="text-lg">→</span>
                                    <span class="text-sm font-semibold">Visualizza Valutazioni</span>
                                </div>
                            </div>
                        </a>

                    </div>
                </section>


                <!-- Nazioni Section -->
                <section class="mb-12">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-3xl">🗺️</span>
                        <h2 class="text-2xl font-bold text-gray-800">Esplora per Nazione</h2>
                    </div>
                    <div class="bg-white rounded-xl shadow-lg p-6"><!-- Search Bar -->
                        <div class="mb-6">
                            <input type="text" placeholder="🔍 Cerca una nazione..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        </div><!-- Countries Grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3 max-h-96 overflow-y-auto">
                            <!-- Country Item Template -->
                            <a href="#" class="country-badge group">
                                <div class="flex flex-col items-center p-4 rounded-lg bg-gray-50 hover:bg-emerald-50 transition-colors border border-gray-200 hover:border-emerald-400">
                                    <img src="https://flagcdn.com/48x36/it.png" alt="Italy" class="w-12 h-9 rounded shadow-sm mb-2" onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 18%22><rect fill=%22%23ddd%22 width=%2224%22 height=%2218%22/></svg>'"> <span class="text-sm font-medium text-gray-700 text-center truncate">Italia</span> <span class="text-xs text-gray-400 mt-1">IT</span>
                                </div>
                            </a>

                        </div>
                        <div class="mt-4 text-center">
                            <a href="#" class="text-emerald-600 hover:text-emerald-700 text-sm font-semibold"> Visualizza tutte le nazioni → </a>
                        </div>
                    </div>
                </section>
                <!-- Quick Stats Section -->
                <section class="mb-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">📊 Statistiche Rapide</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-emerald-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Specie Censite</h3>
                <div class="flex items-baseline gap-2"><span class="text-4xl font-bold text-emerald-600">157K</span> <span class="text-gray-500 text-sm">specie</span>
                </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-red-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Specie in Pericolo</h3>
                <div class="flex items-baseline gap-2"><span class="text-4xl font-bold text-red-600">42K</span> <span class="text-gray-500 text-sm">valutate</span>
                </div>
                </div>
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-600">
                <h3 class="text-gray-600 text-sm font-semibold mb-2">Valutazioni</h3>
                <div class="flex items-baseline gap-2"><span class="text-4xl font-bold text-blue-600">2024</span> <span class="text-gray-500 text-sm">anno</span>
                </div>
                </div>
                </div>
                </section>
            </main>
            <!-- Footer -->
            <footer class="bg-gray-800 text-white mt-auto">
                <div class="max-w-7xl mx-auto px-4 py-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div>
                <h4 class="font-bold text-emerald-400 mb-3">📱 Informazioni API</h4>
                <p class="text-sm text-gray-300">Versione API: <span class="font-semibold">4.0.0</span></p>
                <p class="text-sm text-gray-300">Red List Version: <span class="font-semibold">2024-1</span></p>
                </div>
                <div>
                <h4 class="font-bold text-emerald-400 mb-3">📊 Statistiche</h4>
                <p class="text-sm text-gray-300">Specie Censite: <span class="font-semibold">157,190</span></p>
                <p class="text-sm text-gray-300">Ultimo Aggiornamento: <span class="font-semibold">2024-01-15</span></p>
                </div>
                <div>
                <h4 class="font-bold text-emerald-400 mb-3">🔗 Link Utili</h4>
                <ul class="space-y-1">
                    <li><a href="https://www.iucnredlist.org" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-300 hover:text-emerald-400 transition">IUCN Red List ↗</a></li>
                    <li><a href="https://api.iucnredlist.org" target="_blank" rel="noopener noreferrer" class="text-sm text-gray-300 hover:text-emerald-400 transition">API Documentation ↗</a></li>
                </ul>
                </div>
                <div>
                <h4 class="font-bold text-emerald-400 mb-3">📄 Info</h4>
                <p class="text-sm text-gray-300">IUCN Red List Explorer</p>
                <p class="text-sm text-gray-400 mt-2">Dati forniti da IUCN Red List Database</p>
                </div>
                </div>
                <div class="border-t border-gray-700 pt-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <p class="text-sm text-gray-400">© 2024 IUCN Red List Explorer. Tutti i diritti riservati.</p>
                <div class="flex gap-4"><a href="#" class="text-sm text-gray-300 hover:text-emerald-400 transition">Privacy Policy</a> <a href="#" class="text-sm text-gray-300 hover:text-emerald-400 transition">Termini di Servizio</a> <a href="#" class="text-sm text-gray-300 hover:text-emerald-400 transition">Contatti</a>
                </div>
                </div>
                </div>
                </div>
            </footer>
        </div>
    </body>

</html>
