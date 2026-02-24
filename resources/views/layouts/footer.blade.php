@php
    use \App\Services\Iucn\IucnApiService;
    $iucnFooter = new IucnApiService();
    $footerInfo = $iucnFooter->getFooterInfo();
@endphp
<footer class="bg-gray-800 text-white mt-auto">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div>
                <h4 class="font-bold text-emerald-400 mb-3">📱 Informazioni API</h4>
                <p class="text-sm text-gray-300">Versione API: <span class="font-semibold">{{ $footerInfo['api_version'] }}</span></p>
                <p class="text-sm text-gray-300">Red List Version: <span class="font-semibold">{{ $footerInfo['red_list_version'] }}</span></p>
            </div>
            <div>
                <h4 class="font-bold text-emerald-400 mb-3">📊 Statistiche</h4>
                <p class="text-sm text-gray-300">Specie Censite: <span class="font-semibold">{{ $footerInfo['species_count'] }}</span></p>
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

    </div>
</footer>
