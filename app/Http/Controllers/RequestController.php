<?php


namespace App\Http\Controllers;

use App\Http\Auth;
use App\Http\AuthorizedClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class RequestController
{
    protected $ci;

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
    }

    public function requests(Request $request, Response $response, $args)
    {
        $user_id = Auth::getInstance($this->ci)->userId();

        try {
            $res = (new AuthorizedClient)->request('GET', "/api/v1/users/$user_id/requests");

            $requests = json_decode($res->getBody());
        } catch (\Exception $e) {
            $requests = [];
        }

        return $this->ci->get('view')->render($response, 'requests/home.twig', [
            'requests' => $requests,
        ]);
    }
}
