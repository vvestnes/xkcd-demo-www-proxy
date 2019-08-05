<?php
declare(strict_types=1);

final class Xkcd_ApiTest extends PHPUnit_Framework_TestCase
{
    var $api;
    
    function __construct() {
        parent::__construct();
        $this->api = new Xkcd_api();
    }
    
    public function testFetchCurrentNumIsValid(): void
    {
        $this->assertTrue(
            is_numeric($this->api->fetch_data()['num'])
        );
    }
    
    public function testFetchCurrentJsonIsValid(): void
    {
        $this->assertTrue(
            is_array($this->api->fetch_data())
        );
    }
    
    public function testFetchJson1337(): void
    {
        $this->assertTrue(
            is_array($this->api->fetch_data(1337))
        );
    }

    public function testFetchJson0(): void
    {
        $this->assertFalse(
            is_array($this->api->fetch_data(0))
        );
    }

    public function testFetchJsonSafeTitleIsValid(): void
    {
        $this->assertTrue(
            $this->api->fetch_data(1337)["safe_title"] === "Hack"
        );
    }
    
}
