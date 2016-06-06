<?php

require_once('virgil_http_client.php');


class virgil_resource_client extends virgil_http_client{

    /**
     * Virgil Auth Constructor
     * @param $options
     */
    public function __construct($options)
    {
        $this->options['service_url'] = $options['resource_url'];
    }

    /**
     * Get Resource Data
     * @param $accessToken
     * @param $virgilCardId
     * @return bool
     */
    public function get_resource_data($accessToken, $virgilCardId)
    {
        $result = $this->call(
            self::HTTP_REQUEST_GET, sprintf('/info/%s', $virgilCardId), [], ['Authorization: Bearer ' . $accessToken]
        );

        if($result !== false) {
            return $result['data'];
        }

        return false;
    }
} 