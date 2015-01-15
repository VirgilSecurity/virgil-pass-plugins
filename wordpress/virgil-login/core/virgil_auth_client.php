<?php


class virgil_auth_client {

    /**
     * RESTful allowed methods
     * @var string
     */
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    /**
     * Virgil Auth service
     * @var string
     */
   const SERVICE_URL = 'https://auth.virgilsecurity.com';

    /**
     * Curl call
     *
     * @param $url
     * @param $method
     * @param null $data
     * @return array|bool|mixed
     */
    protected static function call($url, $method, $data = null) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_URL, self::SERVICE_URL . $url);
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
    public static function verify_token($token) {

        $result = self::call(sprintf('/verify-token/%s', $token), self::GET);
        return $result['is_active'];
    }

    /**
     * Get user information by auth token
     * @param $token
     * @return array|bool|mixed
     */
    public static function get_user_info_by_token($token) {

        $result = self::call(sprintf('/token/%s/info', $token), self::GET);
        if(!$result) {
            return false;
        }

        return $result;
    }
} 