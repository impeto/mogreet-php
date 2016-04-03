<?php

use Dotenv\Dotenv;
use Mogreet\Client;

class ChangesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $dotEnv = new Dotenv( dirname( __DIR__), '.env.test');
        $dotEnv->load();
    }

    public function testNoExceptionWhenCreatingObjects()
    {

        $client = new Client();

        $result = $client->system()->ping();

        $this->assertEquals( "success", $result->status);
    }

    public function testSendingAMessage()
    {
        $data = [
            "campaign_id" => getenv( 'MOGREET_TEST_CAMPAIGN'),
            "to" => getenv( 'MOGREET_TEST_NUMBER'),
            "message" => "Hello from the Mogreet PHP Library. You can send SMS now."
        ];

        $result = (new Client)->transaction()->send( $data);

        $this->assertEquals( 'success', $result->status);
    }
}