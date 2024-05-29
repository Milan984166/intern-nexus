<?php
return [
    'client_id' => env('PAYPAL_CLIENT_ID','AUI3JSm6yct2Nr5mad0RoZwN_l7BpRqC4zGDiTDPbLfPigZp1GvCRr_YmScJlusJ_eAxGT4z4SKQ9uat'),
    'secret' => env('PAYPAL_SECRET','EH9W2sk6ITC1EaY73wnPIhpN1mxRSxM5ceQu-023kvXAstfqMrkoXZ0dSqvlp8Z2M0e2heKaF_smHmWz'),
    'settings' => array(
        'mode' => env('PAYPAL_MODE','sandbox'),
        'http.ConnectionTimeOut' => 1000,
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'ERROR'
    ),
];