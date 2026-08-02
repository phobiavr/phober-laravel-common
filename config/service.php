<?php

return [
    'secret' => env('SERVICE_SECRET'),
    'overlay_secret' => env('OVERLAY_SECRET'),
    'notification_url' => env('NOTIFICATION_SERVER_URL', 'http://notification-server'),
];
