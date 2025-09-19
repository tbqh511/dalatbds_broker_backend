<?php

return [
    'project_id' => env('GOOGLE_PLAY_INTEGRITY_PROJECT_ID'),
    'credentials_path' => env('GOOGLE_PLAY_INTEGRITY_CREDENTIALS_PATH', storage_path('app/google-play-integrity-credentials.json')),
    'package_name' => env('GOOGLE_PLAY_INTEGRITY_PACKAGE_NAME'),
];
