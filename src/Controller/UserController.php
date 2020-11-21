<?php

namespace App\Controller;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Exception\RequestException;

class UserController
{
    /**
     * Makes a request to the user Api endpoint
     * 
     * This will get a user by eamil and password. 
     * By communicating with the enpoint, it can get 
     * error feedback such as when a user was not 
     * found or if there are any missing parramters
     * 
     * @param Object
     * @return UserObject
     */

    public static function create($params, $toJson = true) : Array
    {
        try
        {
            $httpClient = HttpClient::create();

            $response = $httpClient->request('POST', 'https://user-api-endpoint.herokuapp.com' . '/user/create', [
                'json' => [
                    'email' => $params['email'],
                    'password' => [
                        'first' => $params['password']['first'],
                        'second' => $params['password']['second']
                    ]
                ]
            ]);

            $jsonData = json_decode($response->getContent(false), true);

            if ($response->getStatusCode() != Response::HTTP_CREATED)
            {
                $error = isset($jsonData['error']) && $jsonData['error'] == 'User already exists.'
                    ? 'Email already taken.'
                    : $jsonData['error'];
                $details = isset($jsonData['details']) ? $jsonData['details'] : "No details.";

                throw new RequestException($error, $response->getStatusCode());
            }
            else
            {
                return $toJson ? $jsonData : json_encode($jsonData);
            }
        }
        catch(ClientException $ce)
        {
            throw new RequestException($ce->getMessage(), $ce->getCode(), $ce);
        }
    }
}