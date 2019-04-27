<?php
namespace Maishapay\Auth;

use Psr\Http\Message\ResponseInterface;

class GuardMiddleware
{
    /**
     * @var \OAuth2\OAuth2Sever
     */
    protected $server;

    public function __construct($server)
    {
        $this->server = $server;
    }

    public function __invoke($request, ResponseInterface $response, $next){
        $server = $this->server;
        $req = \OAuth2\Request::createFromGlobals();

        if (!$server->verifyResourceRequest($req)){
            $server->getResponse()->send();

            $array = [
                "title" => "syntax error",
                "type" => "about:blank",
                "status" => 401
            ];

            die(json_encode($array, JSON_PRETTY_PRINT));
        }

        // store the username into the request's attributes
        $token = $server->getAccessTokenData($req);
        $request = $request->withAttribute('username', $token['user_id']);

        return $next($request, $response);
    }
}
