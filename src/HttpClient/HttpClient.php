<?php
/**
 * Copyright (c) 2019 Verify Technologies Ltd.
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 */
namespace Kuza\Verify\HttpClient;

use GuzzleHttp\Client;

class HttpClient
{

    private $sandbox_url = 'http://localhost:3000/';
    private $live_url = 'https://api.verify.ke/';

    public $client;

    public function __construct($environment = 'sandbox')
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => $environment == 'live' ? $this->live_url : $this->sandbox_url,
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
    }
}