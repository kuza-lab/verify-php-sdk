<?php
/**
 * Copyright (c) 2019 Verify Technologies Ltd.
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 */
namespace Kuza\Verify\Verification;


use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Kuza\Verify\Exceptions\VerifyClientException;
use Kuza\Verify\Exceptions\VerifySdkException;
use Kuza\Verify\Exceptions\VerifyServerException;
use Kuza\Verify\Helpers\DataHelper;
use Kuza\Verify\HttpClient\HttpClient;
use Psr\Http\Message\ResponseInterface;

class NCA
{

    private $verificationResponse;

    public function __construct() {

    }

    /**
     * Very NCA details
     *
     * @param HttpClient $httpClient
     * @param string $token
     * @param array $data
     *
     * @throws VerifyClientException
     * @throws VerifySdkException
     * @throws VerifyServerException
     */
    public function verifyContractor(HttpClient $httpClient, $token, $data) {

        try {

            $headers = [
                "Authorization"     => "Bearer $token",
                "Content-Type"      => 'application/json',
                "Accept"            => 'application/json'
            ];

            /**
             * @var ResponseInterface $response
             */
            $response = $httpClient->client->post("ncaverification", ["headers" => $headers, "body" => json_encode($data)]);

            $responseBody = json_decode($response->getBody()->getContents(), JSON_FORCE_OBJECT);

            if ($responseBody['result_code'] == 1) {
                throw new VerifySdkException($responseBody['message']);
            }

            $this->verificationResponse = $responseBody['data'];

        } catch (ServerException $ex) {

            $responseBody = json_decode($ex->getResponse()->getBody()->getContents(), JSON_FORCE_OBJECT);

            throw new VerifyServerException("Error says: ".implode(',',$responseBody['errors']));

        } catch (ClientException $ex) {

            $responseBody = json_decode($ex->getResponse()->getBody()->getContents(), JSON_FORCE_OBJECT);

            throw new VerifyClientException("Error says: ".implode(',',$responseBody['errors']));
        }
    }

    /**
     * Get all the verification response data
     *
     * @return mixed
     */
    public function getAll() {
        return $this->verificationResponse;
    }

    /**
     * Get the value of a parameter
     *
     * @param $param
     *
     * @return mixed
     */
    private function getParameterValue($param) {

        $nameObject = DataHelper::searchMultiArrayByKeyReturnObject($this->verificationResponse, "parameter_name", $param);

        return $nameObject == null ? [] : $nameObject['parameter_value'];
    }

    /**
     * Check if a parameter is verified or not
     *
     * @param $param
     *
     * @return mixed
     */
    private function isParameterVerified($param) {

        $paramObject = DataHelper::searchMultiArrayByKeyReturnObject($this->verificationResponse, "parameter_name", $param);

        return $paramObject == null ? false : $paramObject['is_verified'];
    }

    /**
     * Get the registration number
     *
     * @return bool
     */
    public function getRegistrationNumber() {
        return $this->getParameterValue("registration_no");
    }

    /**
     * Get the name
     *
     * @return bool
     */
    public function getName() {
        return $this->getParameterValue("contractor_name");
    }

    /**
     * Get the town
     *
     * @return bool
     */
    public function getTown() {
        return $this->getParameterValue("town");
    }

    /**
     * Get the category
     *
     * @return bool
     */
    public function getCategory() {
        return $this->getParameterValue("category");
    }

    /**
     * Get the class
     *
     * @return bool
     */
    public function getClass() {
        return $this->getParameterValue("class");
    }

    /**
     * Check if registration number is verified
     *
     * @return bool
     */
    public function isRegistrationNumberVerified() {
        return $this->isParameterVerified("registration_no");
    }

    /**
     * Check if name is verified
     *
     * @return bool
     */
    public  function isNameVerified() {
        return $this->isParameterVerified('contractor_name');
    }

    /**
     * Check if town is verified
     *
     * @return bool
     */
    public function isTownVerified() {
        return $this->isParameterVerified('town');
    }

    /**
     * Check if category is verified
     *
     * @return bool
     */
    public function isCategoryVerified() {
        return $this->isParameterVerified('category');
    }

    /**
     * Check if class is verified
     *
     * @return bool
     */
    public function isClassVerified() {
        return $this->isParameterVerified('class');
    }
}