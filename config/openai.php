<?php

return [

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION', null),

    'project' => env('OPENAI_PROJECT'),

    'base_uri' => env('OPENAI_BASE_URL'),

    // globalni timeout za zahtev prema OpenAI
    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 60),
];
