<?php

namespace Mogreet;

class System
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function ping(array $params = array()) 
    {
        return $this->client->processRequest('moms', 'system.ping', $params);
    }
}
