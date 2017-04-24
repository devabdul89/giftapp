<?php

return array(

    'ios' => array(
        'environment' => 'development',
        'certificate' => config_path('pem/giftapp.pem'),
        'passPhrase'  => '12345678',
        'service'     => 'apns'
    ),
    'android' => array(
        'environment' =>'development',
        'apiKey'      =>'AIzaSyBb9JK5tx8l-qIe92DK2t7Tnn3qUmY1mQE',
        'service'     =>'gcm'
    )

);