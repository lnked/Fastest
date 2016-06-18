<?php

return array(
    // Имена провайдеров могут быть произвольными,
    // но для простоты примера имя я взял с типа API
    'facebook' => array(
        'type'      => 'facebook',
        'appId'     => '',
        'appSecret' => '',

        // опционально права которые запросить у пользователя,
        'permissions' => array(),
 
        // опционально версия API
        'apiVersion' => '2.3' 
    ),
    'twitter' => array(
        'type' => 'twitter',

        // Twitter работает через OAuth 1.0a
        // поэтому поля отличаются
        'consumerKey' => '',
        'consumerSecret' => ''
    ),
    'google' => array(
        'type'      => 'google',
        'appId'     => '',
        'appSecret' => '',

        // опционально
        'scope'  => array(), 
        'apiVersion' => '2.3' 
    ),
    'vk' => array(
        'type'      => 'vk',
        'appId'     => '',
        'appSecret' => '',

        // опционально
        'scope'  => array(), 
        'apiVersion' => '2.3' 
    ),
);