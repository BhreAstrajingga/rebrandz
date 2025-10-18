<?php

return [
    'base_domain' => parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost',
    'exclude_subdomains' => [
        'www',
        'admin',
    ],
];

