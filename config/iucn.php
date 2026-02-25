<?php

return [
    'base_url' => env('IUCN_API_BASE_URL', 'https://api.iucnredlist.org'),
    'token' => env('IUCN_API_TOKEN'),

    'cache' => [
        'dashboard_ttl' => 3600,
        'footer_ttl' => 86400,
        'default_ttl' => 300,
    ],




];
