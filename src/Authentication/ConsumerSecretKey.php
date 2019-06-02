<?php
/**
 * Copyright (c) 2019 Verify Technologies Ltd.
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 */
namespace Kuza\Verify\Authentication;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Response;
use Kuza\Verify\Exceptions\VerifyClientException;
use Kuza\Verify\Exceptions\VerifySdkException;
use Kuza\Verify\Exceptions\VerifyServerException;
use Kuza\Verify\HttpClient\HttpClient;

class ConsumerSecretKey
{

    private $consumerKey = '';
    private $secretKey = '';

    public function __construct($consumerKey, $secretKey) {
        $this->consumerKey = $consumerKey;
        $this->secretKey = $secretKey;
    }

    /**
     * Generate the token
     *
     * @param HttpClient $httpClient
     *
     * @return string
     *
     * @throws VerifySdkException
     * @throws VerifyServerException
     * @throws VerifyClientException
     */
    public function generateToken(HttpClient $httpClient) {

        $token = '';

        $headers = [
            "consumerKey" => $this->consumerKey,
            "secretKey"     => $this->secretKey
        ];

        try {

            /**
             * @var Response $response
             */
            $response = $httpClient->client->get("oauth", ["headers" => $headers]);

            $responseBody = json_decode($response->getBody()->getContents(), JSON_FORCE_OBJECT);

            if ($responseBody['result_code'] == 1) {
                throw new VerifySdkException($responseBody['message']);
            }
            $token = $responseBody['data']['token'];

        } catch (ServerException $ex) {

            $responseBody = json_decode($ex->getResponse()->getBody()->getContents(), JSON_FORCE_OBJECT);

            throw new VerifyServerException("Error says: ".implode(',',$responseBody['errors']));

        } catch (ClientException $ex) {

            $responseBody = json_decode($ex->getResponse()->getBody()->getContents(), JSON_FORCE_OBJECT);

            throw new VerifyClientException("Error says: ".implode(',',$responseBody['errors']));
        }

        return $token;
    }
}