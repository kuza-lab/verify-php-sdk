<?php

use PHPUnit\Framework\TestCase;

class NCATest extends TestCase {

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
     * Test verification of NCA contractor details
     *
     * @throws \Kuza\Verify\Exceptions\VerifySdkException
     */
    public function testNCAContractorVerification() {

        $data = [
            "registration_no"   => "46733/R/1218",
            "contractor_name"   => "Veneers Company Limited",
            "category"          => "NCA81",
            "class"             => "ROAD WORK"

        ];

        $this->verifyKe->verifyNCAContractor($data);

        // test that all have been verified
        $this->assertEquals(4, sizeof($this->verifyKe->ncaContractor->getAll()));
        // test registration number verified
        $this->assertTrue($this->verifyKe->ncaContractor->isRegistrationNumberVerified());
        //test name verified
        $this->assertTrue($this->verifyKe->ncaContractor->isNameVerified());
        // test category
        $this->assertTrue($this->verifyKe->ncaContractor->isCategoryVerified());
        // test class verified
        $this->assertTrue($this->verifyKe->ncaContractor->isClassVerified());
        // test town now verified
        $this->assertFalse($this->verifyKe->ncaContractor->isTownVerified());
    }

    /**
     * Test unprovided contractor data
     *
     * @throws \Kuza\Verify\Exceptions\VerifyClientException
     * @throws \Kuza\Verify\Exceptions\VerifySdkException
     * @throws \Kuza\Verify\Exceptions\VerifyServerException
     */
    public function testNCAVerificationNoData() {

        $this->expectException(\Kuza\Verify\Exceptions\VerifyClientException::class);

        $data = [];
        $this->verifyKe->verifyNCAContractor($data);
    }
}