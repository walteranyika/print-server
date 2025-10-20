<?php

return [
    'paths' => ['api/*', 'print'],
    'allowed_methods' => ['*'],
//    'allowed_origins' => ['*'], // or restrict later to your IPs
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'allowed_origins' => ['*'],
    'supports_credentials' => true,
];
