<?php


class virgil_core {

    /**
     * Plugin options
     * @var array
     */
    protected $options = array();

    /**
     * Get option name
     * @return string
     */
    public function get_options_name() {
        return 'virgil';
    }

    /**
     * Admin settings page name
     * @return string
     */
    public function get_settings_pagename() {
        return 'virgil_settings';
    }

    /**
     * Default Plugin options
     * @return array
     */
    public function get_default_options() {

        return array(
            'redirect_url' => get_option('siteurl') . '/wp-login.php?token={token}&virgil_card_id={virgil_card_id}',
            'sdk_url'      => get_option('siteurl') . '/wp-content/plugins/virgil_pass/js/pass-runtime.js',
            'resource_url' => 'https://demo-resource-service-stg.virgilsecurity.com',
            'auth_url'     => 'https://auth-stg.virgilsecurity.com/v3'
        );
    }

    /**
     * Get serialized plugin options.
     * @return array
     */
    public function get_plugin_options() {

        if ($this->options != null) {
            return $this->options;
        }

        $this->options = $this->get_default_options();
        foreach((array)get_site_option($this->get_options_name(), array()) as $key => $value) {
            $this->options[$key] = $value;
        }

        return $this->options;
    }

    /**
     * Display WP error
     * @param $wpError
     * @return bool|int|string|WP_Error
     */
    protected function displayAndReturnError($wpError) {

        global $error;
        $error = htmlentities2($wpError->get_error_message($wpError->get_error_code()));
        return $error;
    }
}