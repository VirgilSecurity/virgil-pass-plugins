<?php

require_once('virgil_http_client.php');


class virgil_auth_client extends virgil_http_client {

    /**
     * Virgil Auth Constructor
     * @param $options
     */
    public function __construct($options)
    {
        $this->options['service_url'] = $options['auth_url'];
    }

    /**
     * Obtain Authorization Grant token on a valid Access Token
     * @param $code - Auth access code
     * @return bool|mixed
     */
    public function obtain_access_code($code)
    {
        $result = $this->call(
            self::HTTP_REQUEST_POST, '/authorization/actions/obtain-access-code',
            [
                'grant_type' => 'authorization_code',
                'code' => $code
            ]
        );

        if($result !== false) {
            return $result['access_token'];
        }

        return $result;
    }
}