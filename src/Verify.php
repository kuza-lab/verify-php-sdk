<?php
/**
 * Copyright (c) 2019 Verify Technologies Ltd.
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 */
namespace Kuza\Verify;


use Kuza\Verify\Authentication\ConsumerSecretKey;
use Kuza\Verify\Exceptions\VerifyClientException;
use Kuza\Verify\HttpClient\HttpClient;
use Kuza\Verify\Verification\NCA;
use Kuza\Verify\Verification\UserIdentity;

class Verify
{

    private $token = '';

    protected $httpClient;
    protected $consumerSecretKeyAuth;
    public $userIdentity;
    public $ncaContractor;

    /**
     * Verify constructor.
     *
     * @param $config
     *
     * @throws VerifyClientException
     */
    public function __construct($config)
    {

        if (empty($config['environment'])) {
            throw new VerifyClientException("Please provide the environment");
        }

        if (empty($config['consumer_key'])) {
            throw new VerifyClientException("Please provide the consumer key");
        }

        if (empty($config['secret_key'])) {
            throw new VerifyClientException("Please provide the secret key");
        }

        $this->httpClient = new HttpClient($config['environment']);
        $this->consumerSecretKeyAuth = new ConsumerSecretKey($config['consumer_key'], $config['secret_key']);
        $this->userIdentity = new UserIdentity();
        $this->ncaContractor = new NCA();

    }

    /**
     * Set the token.
     *
     * @throws Exceptions\VerifySdkException
     * @throws Exceptions\VerifyServerException
     * @throws VerifyClientException
     */
    private function setToken() {
        $this->token = $this->consumerSecretKeyAuth->generateToken($this->httpClient);
    }

    /**
     * Get the auth token generated.
     *
     * @return string
     *
     * @throws Exceptions\VerifySdkException
     * @throws Exceptions\VerifyServerException
     * @throws VerifyClientException
     */
    public function getToken() {

        $this->setToken();

        return $this->token;
    }

    /**
     * Verify user data
     *
     * @param $data
     *
     * @throws Exceptions\VerifySdkException
     * @throws Exceptions\VerifyServerException
     * @throws VerifyClientException
     */
    public function verifyUser($data) {

        $this->setToken();

        $this->userIdentity->verifyUser($this->httpClient,$this->token, $data);
    }

    /**
     * Verify NCA Contractor details
     *
     * @param $data
     *
     * @throws Exceptions\VerifySdkException
     * @throws Exceptions\VerifyServerException
     * @throws VerifyClientException
     */
    public function verifyNCAContractor($data) {

        $this->setToken();

        $this->ncaContractor->verifyContractor($this->httpClient,$this->token,$data);
    }
}