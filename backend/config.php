<?php
declare(strict_types=1);

return [
    'database' => [
        'driver' => 'sqlite',
        'path' => __DIR__ . '/database/blesk.sqlite',
    ],

    'admin' => [
        'login' => 'Admin',
        'role_id' => 2,
    ],
];
