<?php


function getDatiDaAPI(){
    $address = getAddress();

    $url = 'http://' . $address . '/www/progetto2/api/ApiSITOPAGAMENTO.php';
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function inviaDatiAPI($dati){
    $address = getAddress();

    $url = 'http://' . $address . '/www/progetto2/api/ApiSITOPAGAMENTO.php';

    $options = [
        'http' => [
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($dati)
        ]
    ];


    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    return json_decode($response, true);

}

function getAddress(){
    return $address = 'localhost:41062';
}
