<?php

use PHPUnit\Framework\TestCase;

class UserIdentityTest extends TestCase {

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
    public function testUserVerification() {

        $data = [
            "id_number" => "1234",
            "phone_number" => "254729941254",
            "first_name" =>  "Phelix",
            "surname" => "Omondi",
            "other_name" => "Juma",
            "date_of_birth" => "2019-01-01"
        ];

        $this->verifyKe->verifyUser($data);

        // test that all have been verified
        $this->assertEquals(4, sizeof($this->verifyKe->userIdentity->getAll()));
        // test name value
        $this->assertEquals('Phelix Omondi Juma', $this->verifyKe->userIdentity->getName());
        // test name verified
        $this->assertTrue($this->verifyKe->userIdentity->isNameVerified());
        //test phone number verified
        $this->assertTrue($this->verifyKe->userIdentity->isPhoneNumberVerified());
        // test citizenship
        $this->assertFalse($this->verifyKe->userIdentity->isCitizenshipVerified());
    }

    /**
     * Test unprovided user data
     *
     * @throws \Kuza\Verify\Exceptions\VerifyClientException
     * @throws \Kuza\Verify\Exceptions\VerifySdkException
     * @throws \Kuza\Verify\Exceptions\VerifyServerException
     */
    public function testUserVerificationNoUser() {

        $this->expectException(\Kuza\Verify\Exceptions\VerifyClientException::class);

        $data = [];
        $this->verifyKe->verifyUser($data);
    }
}