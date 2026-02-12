<?php

return [

	'paths' => ['api/*'],

	'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'],

	'allowed_origins' => [env('APP_URL', 'http://localhost')],

	'allowed_origins_patterns' => [],

	'allowed_headers' => ['Content-Type', 'Authorization', 'X-CSRF-TOKEN', 'X-Requested-With', 'X-XSRF-TOKEN'],

	'exposed_headers' => [],

	'max_age' => 0,

	'supports_credentials' => true,

];
