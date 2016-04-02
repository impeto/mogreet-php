<?php

namespace Mogreet;

class Mogreet
{
    const USER_AGENT = 'mogret-php/1.0';
    const BASE_API = 'https://api.mogreet.com';

    private $clientId;
    private $token;
    private $defaultFormat;

    /*
     * API Objects
     */
    private $keyword;
    private $media;
    private $system;
    private $transaction;
    private $user;
    private $list;


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
    }

    public function processRequest($base, $api, array $params = array(), $multipart = false) 
    {
        // TODO implement flag to do post/get
        $params = array_merge($params, $this->_getDefaultApiParams());
        $data = Request::postRequest($base, $api, $params, $multipart);
        return new Response($params['format'], $data);
    }

    /**
     * @return Keyword
     */
    public function keyword()
    {
        if( ! $this->keyword ) {
            $this->keyword = new Keyword( $this);
        }

        return $this->keyword;
    }

    /**
     * @return Media
     */
    public function media()
    {
        if ( ! $this->media){
            $this->media = new Media( $this);
        }
        return $this->media;
    }

    /**
     * @return System
     */
    public function system()
    {
        if ( ! $this->system){
            $this->system = new System( $this);
        }
        return $this->system;
    }

    /**
     * @return Transaction
     */
    public function transaction()
    {
        if ( ! $this->transaction){
            $this->transaction = new Transaction( $this);
        }

        return $this->transaction;
    }

    /**
     * @return User
     */
    public function user()
    {
        if ( ! $this->user){
            $this->user = new User( $this);
        }

        return $this->user;
    }

    /**
     * @return MoList
     */
    public function lst()
    {
        if ( ! $this->list){
            $this->list = new MoList( $this);
        }

        return $this->list;
    }
    protected function _getDefaultApiParams() 
    {
        return [ "client_id" => $this->clientId, "token" => $this->token, "format" => $this->defaultFormat ];
    }
}