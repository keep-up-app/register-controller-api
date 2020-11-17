<?php

namespace App\Controller;

use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Exception;

class ApiController
{
    /**
     * @var String
     */
    private $apiUrl = 'https://user-api-endpoint.herokuapp.com';
    
    /**
     * @var Array
     */
    private $content;
    
    /**
     * @var Integer
     */
    private $statusCode;

    /**
     * @var String
     */
    private $errorMessage;

    /**
     * Returns request content;
     */
    public function getJsonContent()
    {
        return $this->content;
    }

    /**
     * Returns status code if request failed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Returns error messag eif request failed 
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Makes a request to the user Api endpoint
     * 
     * This will get a user by eamil and password. 
     * By communicating with the enpoint, it can get 
     * error feedback such as when a user was not 
     * found or if there are any missing parramters
     * 
     * @param Object
     */
    public function request($params)
    {
        try
        {
            $httpClient = HttpClient::create();

            $response = $httpClient->request('POST', $this->apiUrl . '/user/create', [
                'json' => [
                    'email' => $params['email'],
                    'password' => [
                        'first' => $params['first'],
                        'second' => $params['second']
                    ]
                ]
            ]);
            
            $this->statusCode = $response->getStatusCode();
            $jsonData = json_decode($response->getContent(false), true);
            
            if ($response->getStatusCode() != 200)
            {
                if (isset($jsonData['error']) && $jsonData['error'] == 'User already exists.') $this->errorMessage = 'Email already taken.'; 
                else $this->errorMessage = $jsonData['error']; 
            }
            else
            {
                $this->content = $jsonData;
            }

        } catch(ClientException $ce)
        {
            $this->statusCode = $ce->getCode();
            $this->errorMessage = $ce->getMessage();
        }
    }
}