<?php

namespace App\Services;

//require 'vendor/autoload.php';

use Exception;
use GuzzleHttp\Client;

class loginDspaceService{
    private $url;
    private $data;
    private $client;

    public function loginDspace()
    {
        try {
            $client=new Client();
            $this->data=[
                'email'=>env('DSPACE_EMAIL'),
                'password'=>env('DSPACE_PASSWORD')
            ];
            //echo "Iniciando login en {", env('URLSERVIDOR').'/rest/login' ,"}\n";
            $this->url= env('URLSERVIDOR').'/rest/login';
            $response = $client->post($this->url, [
                'form_params' => $this->data
            ]);
            $cookies = $response->getHeader('Set-Cookie');
            //echo "Cookies: " . implode(', ', $cookies) . "\n";
            return $cookies;
        } catch (Exception $e) {
            echo "No se pudo iniciar sesión en el servidor:\n";
            echo $e->getMessage();
            exit(1);
        }
    }
}
