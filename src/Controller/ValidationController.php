<?php

namespace App\Controller;

use Symfony\Component\HttpClient\Exception\ClientException;
use App\Controller\Exception\InvalidInputException;
use Symfony\Component\HttpFoundation\Response;

class ValidationController
{
    public static function make($params)
    {
        $params = self::flatten($params);

        if ($params == null || !isset($params['first']) || !isset($params['second']) || !isset($params['email']))
        {
            throw new InvalidInputException('Missing fields.', 400);
        }

        foreach($params as $key => $value)
        {
            if ($value == null || $value == '')
            {
                if ($key === 'first' || $key === 'second') $key = 'password';
                throw new InvalidInputException('Missing ' . ucfirst($key) . '.', 400);
            }
        }

        unset($value);
    }

    private static function flatten($params, $flattened = [])
    {
        $keys = array_keys($params);

        for ($i = 0; $i < count($keys); $i++)
        {
            $value = $params[$keys[$i]];

            if (is_array($value)) 
            {
                return self::flatten($value, $flattened);
            }
            else
            {
                $flattened[$keys[$i]] = $value;
            }
        }
        
        return $flattened;
    }
}