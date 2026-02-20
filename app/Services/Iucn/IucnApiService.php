<?php

namespace App\Services\Iucn;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IucnApiService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('iucn.base_url'), '/');
        $this->token = config('iucn.token');

        if (!$this->token) {
            throw new \Exception("IUCN_API_TOKEN mancante in .env");
        }
    }

    /**
     * Wrapper GET con cache + ritorno (data + headers)
     */
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

    private function cacheKey(string $endpoint, array $query): string
    {
        ksort($query);
        $hash = md5(json_encode($query));
        return "iucn:" . Str::slug($endpoint, '_') . ":" . $hash;
    }

    /**
     * Lista countries
     */
    public function getCountries(): array
    {
        $ttl = config('iucn.cache.dashboard_ttl', 3600);

        // Endpoint indicativo: adattalo a swagger se differente
        $res = $this->get('/api/v4/countries', [], $ttl);

        return $res['data'] ?? [];
    }

    /**
     * Lista systems
     */
    public function getSystems(): array
    {
        $ttl = config('iucn.cache.dashboard_ttl', 3600);

        // Endpoint indicativo: adattalo a swagger se differente
        $res = $this->get('/api/v4/systems', [], $ttl);

        return $res['data'] ?? [];
    }
    /**
     * Assessments by system
     */
    public function getAssessmentsBySystem(string $systemKey, array $filters, int $page = 1, int $perPage = 20): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $query = [
            'page' => $page,
            'per_page' => $perPage,
            // systemKey: l’API potrebbe chiamarlo system o realm
            'system' => $systemKey,
        ];

        // Filtri
        if (!empty($filters['year'])) $query['year_published'] = (int)$filters['year'];
        if (!empty($filters['pe'])) $query['possibly_extinct'] = 1;
        if (!empty($filters['pew'])) $query['possibly_extinct_in_the_wild'] = 1;

        $res = $this->get('/api/v4/assessments', $query, $ttl);

        $items = $res['data']['result'] ?? $res['data'] ?? [];

        $pagination = IucnPaginator::fromHeaders($res['headers'], $page, $perPage);

        return [
            'items' => $items,
            'pagination' => $pagination,
            'raw_headers' => $res['headers'],
        ];
    }

    /**
     * Assessments by country
     */
    public function getAssessmentsByCountry(string $iso2, array $filters, int $page = 1, int $perPage = 20): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $query = [
            'page' => $page,
            'per_page' => $perPage,
            'country' => strtoupper($iso2),
        ];

        if (!empty($filters['year'])) $query['year_published'] = (int)$filters['year'];
        if (!empty($filters['pe'])) $query['possibly_extinct'] = 1;
        if (!empty($filters['pew'])) $query['possibly_extinct_in_the_wild'] = 1;

        $res = $this->get('/api/v4/assessments', $query, $ttl);

        $items = $res['data']['result'] ?? $res['data'] ?? [];

        $pagination = IucnPaginator::fromHeaders($res['headers'], $page, $perPage);

        return [
            'items' => $items,
            'pagination' => $pagination,
            'raw_headers' => $res['headers'],
        ];
    }

    /**
     * Dettaglio assessment
     */
    public function getAssessment(string $assessmentId): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $res = $this->get("/api/v4/assessments/{$assessmentId}", [], $ttl);

        return $res['data'] ?? [];
    }

    /**
     * Dettaglio taxa/sis
     */
    public function getTaxonSis(string $sisTaxonId): array
    {
        $ttl = config('iucn.cache.default_ttl', 300);

        $res = $this->get("/api/v4/taxa/sis/{$sisTaxonId}", [], $ttl);

        return $res['data'] ?? [];
    }

    /**
     * Footer info (cache 1 giorno)
     */
    public function getFooterInfo(): array
    {
        $ttl = config('iucn.cache.footer_ttl', 86400);

        // Endpoint indicativi: verifica swagger
        $api = $this->get('/api/v4/info', [], $ttl);
        $stats = $this->get('/api/v4/stats', [], $ttl);

        return [
            'api_version' => $api['data']['api_version'] ?? null,
            'red_list_version' => $api['data']['red_list_version'] ?? null,
            'species_count' => $stats['data']['species_count'] ?? null,
        ];
    }
}
