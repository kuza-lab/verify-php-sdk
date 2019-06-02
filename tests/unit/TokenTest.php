<?php

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase {

    /**
     * @var \Kuza\Verify\Verify
     */
    protected $verifyKe;

    /**
     * Set up the class
     *
     * @throws \Kuza\Verify\Exceptions\VerifyClientException
     */
    public function setUp() {

        $this->verifyKe = new \Kuza\Verify\Verify([
            "environment"   => 'sandbox',
            "consumer_key"  => 'wertQwscvbpothdkplkjhuqswrtycvgt',
            "secret_key"    => 'lkiutvbQaXtvcplRtbnUWspolkRfhsdu'
        ]);
    }

    /**
     * Test generation of token
     *
     * @throws \Kuza\Verify\Exceptions\VerifySdkException
     */
    public function testConsumerSecretKeyToken() {

        $token = '';

        try {

            $token = $this->verifyKe->getToken();

        } catch (\Kuza\Verify\Exceptions\VerifyClientException $ex) {
            print $ex->getMessage();
        } catch (\Kuza\Verify\Exceptions\VerifyServerException $ex) {
            print $ex->getMessage();
        }

        $this->assertGreaterThan(0, strlen($token));
    }
}