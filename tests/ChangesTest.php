<?php

use Dotenv\Dotenv;
use Mogreet\Mogreet;

class ChangesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $dotEnv = new Dotenv( dirname( __DIR__), '.env.test');
        $dotEnv->load();
    }

    public function testNoExceptionWhenCreatingObjects()
    {

        $client = new Mogreet();

        $result = $client->system->ping();

        $this->assertEquals( "success", $result->status);
    }

    public function testSendingAMessage()
    {
        $data = [
            "campaign_id" => getenv( 'MOGREET_CAMPAIGN'),
            "to" => getenv( 'MOGREET_TEST_NUMBER'),
            "message" => "Hello from KNR. This is just a test"
        ];

        $result = (new Mogreet)->transaction->send( $data);

        $this->assertEquals('success', $result->status);
    }
}