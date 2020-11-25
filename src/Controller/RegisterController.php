<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\Exception\InvalidInputException;
use App\Controller\Exception\RequestException;
use App\Controller\ValidationController as Validator;
use App\Controller\TwoFactorAuthController as TFAC;
use App\Controller\UserController as User;

class RegisterController extends AbstractController
{
    /**
     * @Route("/", methods={"POST"})
     */
    public function index(Request $request) : Response
    {
        $params = json_decode($request->getContent(), true);

        try
        {
            Validator::make($params, ['email', 'password' => ['first', 'second']]);

            $userData = [
                'email' => $params['email'],
                'password' => [
                    'first' => $params['password']['first'],
                    'second' => $params['password']['second']
                ],
            ];

            $user = User::create($userData);
            unset($user{'password'});

            return new Response(
                json_encode($user),
                Response::HTTP_OK,
                ['content-type' => 'application/json']
            );
        }
        catch(InvalidInputException $ex)
        {
            return new Response(
                json_encode(['error' => $ex->getMessage()]),
                $ex->getCode(),
                ['content-type' => 'application/json']
            );
        }
        catch(RequestException $ex)
        {
            $errorContent = [
                'error' => $ex->getMessage(),
                'details' => $ex->getDetails()
            ];

            return new Response(
                json_encode($errorContent),
                $ex->getCode(),
                ['content-type' => 'application/json']
            );
        }
    }
}