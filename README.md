Verify.ke PHP SDK
=================

The official PHP Library for Verify.ke (https://documentation.verify.ke) 

This library allows you to handle verification of data as provided by Verify.ke.

Requirements
============

* PHP >= 5.5.0

Installation
============

    composer require kuza/verify-sdk

Usage
=====


```php
<?php

    require_once  'vendor/autoload.php';
    
    try {
    
        $this->verifyKe = new \Kuza\Verify\Verify([
            "environment"   => 'sandbox',
            "consumer_key"  => 'wertQwscvbpothdkplkjhuqswrtycvgt',
            "secret_key"    => 'lkiutvbQaXtvcplRtbnUWspolkRfhsdu'
        ]);
    
        // Example 1:  verify user details
    
        $data = [
            "id_number" => "1234",
            "phone_number" => "254729941254",
            "first_name" =>  "Phelix",
            "surname" => "Omondi",
            "other_name" => "Juma",
            "date_of_birth" => "2019-01-01"
        ];
    
        $this->verifyKe->verifyUser($data);
    
        // get the verification response
        $userVerificationResponse = $this->verifyKe->userIdentity->getAll();
    
        // check if id number is verified
        $isIdNumberVerified = $this->verifyKe->userIdentity->isIdNumberVerified();
    
    
        // Example 2: verify NCA contractor details.
        
        $data = [
            "registration_no"   => "46733/R/1218",
            "contractor_name"   => "Veneers Company Limited",
            "category"          => "NCA81",
            "class"             => "ROAD WORK"
    
        ];
    
        $this->verifyKe->verifyNCAContractor($data);
    
        // get all nca verification response data
        $ncaVerificationResponse = $this->verifyKe->ncaContractor->getAll();
    
        // check if name is verified
        $isNameVerified = $this->verifyKe->ncaContractor->isNameVerified();
    
    } catch (\Kuza\Verify\Exceptions\VerifyClientException $ex) {
        print $ex->getMessage();
    } catch (\Kuza\Verify\Exceptions\VerifySdkException $ex) {
        print $ex->getMessage();
    }
    
?>
```
    

Credits
=======

* Phelix Juma <jumaphelix@kuzalab.com>
