<?php

/**
 * @package   	Virgil Pass
 * @copyright 	Copyright 2015 http://www.virgilsecurity.com - All rights reserved.
 * @license     BSD
 */

class virgil_auth_client {

    /**
     * Plugin options
     * @var array
     */
    protected $options = array();

    /**
     * Virgil Auth Constructor
     * @param $options
     */
    public function __construct($options) {

        $this->options = $options;
    }

    /**
     * Curl call
     *
     * @param $url
     * @return array|bool|mixed
     */
    protected function call($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_URL, $this->options['auth_url'] . $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $data = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return ($code >= 200 && $code < 300) ? json_decode($data, true) : false;
    }

    /**
     * Verify auth token
     * @param $token
     * @return mixed
     */
    public function verify_token($token) {

        $result = $this->call(sprintf('/verify-token/%s', $token));
        return $result['is_active'];
    }

    /**
     * Get user information by auth token
     * @param $token
     * @return array|bool|mixed
     */
    public function get_user_info_by_token($token) {

        $result = $this->call(sprintf('/token/%s/info', $token));
        if(!$result) {
            return false;
        }

        return $result;
    }
}