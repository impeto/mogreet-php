<?php

namespace Mogreet;

class Mogreet
{
    const USER_AGENT = 'mogret-php/1.0';
    const BASE_API = 'https://api.mogreet.com';

    private $clientId;
    private $token;
    private $defaultFormat;

    public function __construct($clientId = false, $token = false)
    {
        if ( !$clientId || !$token){
            $this->clientId      = getenv( 'MOGREET_CLIENT_ID');
            $this->token         = getenv( 'MOGREET_TOKEN');
        } else {
            $this->clientId      = $clientId;
            $this->token         = $token;
        }

        $this->defaultFormat = 'json';
        $this->keyword       = new Keyword($this);
        $this->media         = new Media($this);
        $this->system        = new System($this);
        $this->transaction   = new Transaction($this);
        $this->user          = new User($this);
        $this->list          = new MoList($this);
    }

    public function processRequest($base, $api, array $params = array(), $multipart = false) 
    {
        // TODO implement flag to do post/get
        $params = array_merge($params, $this->_getDefaultApiParams());
        $data = Request::postRequest($base, $api, $params, $multipart);
        return new Response($params['format'], $data);
    }

    protected function _getDefaultApiParams() 
    {
        return [ "client_id" => $this->clientId, "token" => $this->token, "format" => $this->defaultFormat ];
    }
}