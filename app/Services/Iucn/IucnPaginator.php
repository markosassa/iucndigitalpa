<?php

namespace App\Services\Iucn;

class IucnPaginator
{
    /**
     * Estrae info paginazione dagli header.
     *
     * Questa classe è volutamente "tollerante":
     * se non trova certi header, ritorna comunque una struttura coerente.
     */
    public static function fromHeaders(array $headers, int $page, int $perPage): array
    {
        // Normalizza header keys in lowercase
        $h = [];
        foreach ($headers as $key => $value) {
            $h[strtolower($key)] = is_array($value) ? $value[0] : $value;
        }

        // Header comuni (possono variare)
        // Esempi tipici:
        // x-total-count: 1234
        // x-per-page: 20
        // x-page: 1
        // link: <...page=2>; rel="next", <...page=1>; rel="prev"

        $total = self::intOrNull($h['total-count'] ?? $h['total'] ?? null);
        $perPageFromHeader = self::intOrNull($h['x-per-page'] ?? $h['per-page'] ?? null);
        $pageFromHeader = self::intOrNull($h['x-page'] ?? $h['page'] ?? null);

        if ($perPageFromHeader) $perPage = $perPageFromHeader;
        if ($pageFromHeader) $page = $pageFromHeader;


        // fallback calcolo hasNext se abbiamo total
        if ($total !== null) {
            $maxPages = (int) ceil($total / max(1, $perPage));
            $hasNext = $page < $maxPages;
            $hasPrev = $page > 1;
        }

        return [
            'page' => $page,
            'per_page' => $perPage,
            'total' => $total,

        ];
    }


    private static function intOrNull($v): ?int
    {
        if ($v === null) return null;
        if ($v === '') return null;
        if (!is_numeric($v)) return null;
        return (int) $v;
    }
}
