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

class UserIdentity
{

    private $verificationResponse;

    public function __construct() {

    }

    /**
     * Very user details
     *
     * @param HttpClient $httpClient
     * @param string $token
     * @param array $data
     *
     * @throws VerifyClientException
     * @throws VerifySdkException
     * @throws VerifyServerException
     */
    public function verifyUser(HttpClient $httpClient, $token, $data) {

        try {

            $headers = [
                "Authorization"     => "Bearer $token",
                "Content-Type"      => 'application/json',
                "Accept"            => 'application/json'
            ];

            /**
             * @var ResponseInterface $response
             */
            $response = $httpClient->client->post("userverification", ["headers" => $headers, "body" => json_encode($data)]);

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
     * Get the name
     *
     * @return bool
     */
    public function getName() {
        return $this->getParameterValue("name");
    }

    /**
     * Check if name is verified
     *
     * @return bool
     */
    public function isNameVerified() {
        return $this->isParameterVerified("name");
    }

    /**
     * Check if id number is verified
     *
     * @return bool
     */
    public  function isIdNumberVerified() {
        return $this->isParameterVerified('id_number');
    }

    /**
     * Check if phone number is verified
     *
     * @return bool
     */
    public function isPhoneNumberVerified() {
        return $this->isParameterVerified('phone_number');
    }

    /**
     * Check if citizenship is verified
     *
     * @return bool
     */
    public function isCitizenshipVerified() {
        return $this->isParameterVerified('citizenship');
    }

    /**
     * Check if date of birth is verified
     *
     * @return bool
     */
    public function isDateOfBirthVerified() {
        return $this->isParameterVerified('date_of_birth');
    }

}