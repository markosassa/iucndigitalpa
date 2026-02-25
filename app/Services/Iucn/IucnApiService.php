<?php

namespace App\Services\Iucn;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IucnApiService
{
    private string $baseUrl;
    private string $token;

    // Nel costruttore, inizializziamo la base URL e il token di autenticazione per l'API IUCN.
    // Il token viene prelevato dalle variabili d'ambiente, e se non è presente viene lanciata un'eccezione per evitare errori di autenticazione nelle richieste
    public function __construct()
    {
        $this->baseUrl = rtrim(config('iucn.base_url'), '/');
        $this->token = config('iucn.token');

        if (!$this->token) {
            throw new \Exception("IUCN_API_TOKEN mancante in .env");
        }
    }

    // Metodo generico per effettuare richieste GET con caching
    private function get(string $endpoint, array $query = [], int $ttl = 300): array
    {
        $cacheKey = $this->cacheKey($endpoint, $query);

        return Cache::remember($cacheKey, $ttl, function () use ($endpoint, $query) {
            $url = $this->baseUrl . $endpoint;

            $response = Http::timeout(15)
                ->acceptJson()
                ->withToken($this->token)
                ->get($url, $query);

            if (!$response->successful()) {
                throw new \Exception("Errore IUCN API: {$response->status()} - {$response->body()}");
            }

            return [
                'data' => $response->json(),
                'headers' => $response->headers(),
            ];
        });
    }
    // Genera una chiave di cache unica basata sull'endpoint e sui parametri della query, ordinando i parametri per garantire coerenza.

    private function cacheKey(string $endpoint, array $query): string
    {
        ksort($query);
        $hash = md5(json_encode($query));
        return "iucn:" . Str::slug($endpoint, '_') . ":" . $hash;
    }

    // Metodo per ottenere la lista dei paesi, con caching per 1 ora, chiave fissa 'dashboard.countries'. Restituisce un array di paesi.
    public function getCountries(): array
    {
        $ttl = config('iucn.cache.dashboard_ttl', 3600);

        // Endpoint indicativo: adattalo a swagger se differente
        $res = $this->get('/api/v4/countries', [], $ttl);

        return $res['data'] ?? [];
    }

    // Metodo per ottenere la lista dei sistemi, con caching per 1 ora, chiave fissa 'dashboard.systems'. Restituisce un array di sistemi.
    public function getSystems(): array
    {
        $ttl = config('iucn.cache.dashboard_ttl', 3600);

        $res = $this->get('/api/v4/systems', [], $ttl);

        return $res['data'] ?? [];
    }


    // Metodo per ottenere le valutazioni associate a un sistema specifico, con caching per 5 minuti, chiave basata su system, page, per_page e filtri (year, pe, pew). Restituisce un array di valutazioni e i dati del sistema.
    public function getAssessmentsBySystem(string $systemKey, array $filters = null, int $page = 1, int $perPage = 20): array  {
        $ttl = config('iucn.cache.default_ttl', 300);

        $query = [
            'page' => $page,
            'per_page' => $perPage,

        ];

        if (!empty($filters['year'])) $query['year_published'] = (int)$filters['year'];
        if (!empty($filters['pe'])) $query['possibly_extinct'] = $filters['pe'];
        if (!empty($filters['pew'])) $query['possibly_extinct_in_the_wild'] = $filters['pew'];
        //$query['code'] = $systemKey;

        $res = $this->get("/api/v4/systems/{$systemKey}", $query, $ttl);

        $items = $res['data']['result'] ?? $res['data'] ?? [];

        $pagination = IucnPaginator::fromHeaders($res['headers'], $page, $perPage);

        return [
            'items' => $items,
            'pagination' => $pagination,
            'raw_headers' => $res['headers'],
        ];
    }

     // Metodo per ottenere le valutazioni associate a un paese specifico, con caching per 5 minuti, chiave basata su country, page, per_page e filtri (year, pe, pew). Restituisce un array di valutazioni e i dati del paese.
    public function getAssessmentsByCountry(string $iso2, array $filters = null, int $page = 1, int $perPage = 20): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $query = [
            'page' => $page,
            'per_page' => $perPage,

        ];

        if (!empty($filters['year'])) $query['year_published'] = (int)$filters['year'];
        if (!empty($filters['pe'])) $query['possibly_extinct'] = $filters['pe'];
        if (!empty($filters['pew'])) $query['possibly_extinct_in_the_wild'] = $filters['pew'];


        $res = $this->get('/api/v4/countries/'.strtoupper($iso2), $query, $ttl);

        $items = $res['data']['result'] ?? $res['data'] ?? [];

        $pagination = IucnPaginator::fromHeaders($res['headers'], $page, $perPage);

        return [
            'items' => $items,
            'pagination' => $pagination,
            'raw_headers' => $res['headers'],
        ];
    }

    // Metodo per ottenere i dettagli di una valutazione specifica, con caching per 5 minuti, chiave basata su assessmentId. Restituisce un array con i dati della valutazione.
    public function getAssessment(string $assessmentId): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $res = $this->get("/api/v4/assessment/".$assessmentId, [], $ttl);

        return $res['data'] ?? [];
    }

    // Metodo per ottenere i dettagli di una specie specifica tramite il suo SIS Taxon ID, con caching per 5 minuti, chiave basata su sisTaxonId. Restituisce un array con i dati della specie.
    public function getTaxonSis(string $sisTaxonId, array $filters = null, int $page = 1, int $perPage = 20): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $res = $this->get("/api/v4/taxa/sis/{$sisTaxonId}", [], $ttl);

        return $res['data'] ?? [];
    }

    // Metodo per ottenere informazioni generali sull'API e statistiche, con caching per 24 ore, chiave fissa 'footer_info'. Restituisce un array con le informazioni da visualizzare nel footer.
    public function getFooterInfo(): array
    {
        $ttl = config('iucn.cache.footer_ttl', 86400);

        $api = $this->get('/api/v4/information/api_version', [], $ttl);
        $stats = $this->get('/api/v4/statistics/count', [], $ttl);
        $redlist = $this->get('/api/v4/information/red_list_version', [], $ttl);
        return [
            'api_version' => $api['data']['api_version'] ?? null,
            'red_list_version' => $redlist['data']['red_list_version'] ?? null,
            'species_count' => $stats['data']['count'] ?? null,
        ];
    }
}
