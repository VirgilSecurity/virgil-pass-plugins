<?php

class virgil_http_client {

    /**
     * Plugin options
     * @var array
     */
    protected $options = array();

    /**
     * HTTP Request Methods
     */
    const HTTP_REQUEST_POST = 'POST';
    const HTTP_REQUEST_GET  = 'GET';

    /**
     * Make CURL call
     * @param $method - HTTP method
     * @param $endpoint - endpoint to call
     * @param array $data - request data
     * @param array $headers - request headers
     * @return bool|mixed
     */
    protected function call($method, $endpoint, $data = array(), $headers = array())
    {
        $jsonData = [];
        if($method == self::HTTP_REQUEST_POST) {
            $jsonData = json_encode($data);
            $headers = array_merge(
                [
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)
                ],
                $headers
            );
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $this->options['service_url'] . $endpoint);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        var_dump($data, $code);
        return ($code >= 200 && $code < 300) ? json_decode($data, true) : false;
    }
}