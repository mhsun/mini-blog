<?php

return [
    'name'       => env('ADMIN_NAME', 'Admin'),
    'email'      => env('ADMIN_EMAIL', 'admin@example.com'),
    'import_url' => env('IMPORT_URL', 'http://localhost:8000'),
    'notify'     => env('NOTIFY_ADMIN', false),
];
