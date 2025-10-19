<?php

return [
    'paths' => ['api/*', 'print'],
    'allowed_methods' => ['*'],
//    'allowed_origins' => ['*'], // or restrict later to your IPs
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'allowed_origins' => ['http://127.0.0.1:8001'],
    'supports_credentials' => true,
];
