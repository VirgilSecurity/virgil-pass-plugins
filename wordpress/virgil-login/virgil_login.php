<?php
/**
 * Plugin Name: Virgil Auth plugin
 * Plugin URI: http://virgilsecurity.com/
 * Description: Authentication plugin
 * Version: 1.0
 * Author: Virgil Security
 * Author URI: http://virgilsecurity.com
 * License: GPL
 */

require_once(plugin_dir_path(__FILE__) . '/core/virgil_core.php' );

class virgil_login extends virgil_core {

    protected $PLUGIN_VERSION = '0.1';

    // Singleton
    private static $instance = null;

    private $doneIncludePath = false;

    /**
     * Singleton method
     * @return null|virgil_login
     */
    public static function getInstance() {

        if (null == self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Protected __construct for Singleton
     */
    protected function __construct() {

        $this->add_actions();
        $this->add_hooks();
    }

    /**
     * Attach plugin actions
     */
    protected function add_actions() {

        add_action('init', array($this, 'init'));

        add_action('login_form', array($this, 'create_login_form'));

        add_action('lostpassword_form', array($this, 'lost_password_form'));
        add_action('lostpassword_post', array($this, 'lost_password_post'));

        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));

        add_filter('login_redirect', array($this, 'login_redirect'), 5, 3 );
        add_filter('plugin_action_links', array($this, 'plugin_action_links'), 2, 2);
    }

    /**
     * Attach plugin hooks
     */
    protected function add_hooks() {

        register_activation_hook($this->get_plugin_base_name(), array($this, 'activation_hook'));
        register_deactivation_hook($this->get_plugin_base_name(), array($this, 'deactivation_hook'));
    }

    /**
     * Activation hook. Initialize plugin
     */
    public function activation_hook() {
        add_option('virgil');
    }

    /**
     * Deactivation hook. Remove plugin stuff.
     */
    public function deactivation_hook() {
        delete_option('virgil');
    }

    /**
     * Initialize frontend part
     */
    public function init() {

        wp_enqueue_script('jquery');
        wp_enqueue_script('virgil_sdk', 'https://auth-demo.virgilsecurity.com/js/sdk.js');

        wp_enqueue_style('front_css', $this->get_plugin_url() . '/css/front.css');
    }

    /**
     * Create login HTML code.
     * Append in to the Wordpress HTML structure
     */
    public function create_login_form() {

        $options = $this->get_plugin_options();

        echo '
        <p class="virgil-login">
            <a href="https://auth-demo.virgilsecurity.com/uploads/virgil-chrome.zip">Download Virgil Extension</a>
            <button data-virgil-ui="auth-btn" data-virgil-reference="' . $options['redirect_url'] . '">Virgil Auth</button>
        </p>';

        wp_enqueue_script('login', $this->get_plugin_url() . '/js/login.js');
    }

    /**
     * Initialize admin page stuff
     */
    public function admin_init() {

        register_setting('virgil_settings', $this->get_options_name(), array($this, 'admin_options_validate'));
    }

    /**
     * Add link to the Admin Plugin page
     */
    public function admin_menu() {

        add_options_page(
            'Settings Admin',
            'Virgil Auth',
            'manage_options',
            $this->get_settings_pagename(),
            array($this, 'create_admin_settings_page')
        );
    }

    /**
     * Create HTML structure for Admin Plugin page
     */
    public function create_admin_settings_page() {

        wp_enqueue_style('admin_css', $this->get_plugin_url() . 'css/admin.css');

        $options = $this->get_plugin_options();

        ?>
        <div class="wrap">
            <h2>Virgil Auth Settings</h2>
            <form method="post" action="options.php">
                <? settings_fields($this->get_settings_pagename()) ?>
                <? echo '<label for="virgil_redirect_url" class="textinput big">Redirect URL:</label>'; ?>
                <? echo "<input id='virgil_redirect_url' class='textinput' name='" . $this->get_options_name() . "[redirect_url]' size='68' type='text' value='" . $options['redirect_url'] . "' />"; ?>
                <br class="clear" />
                <p class="submit">
                    <input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * Add Admin Plugin settings link
     * @param $actions
     * @param $file
     * @return mixed
     */
    public function plugin_action_links($actions, $file) {

        if(false !== strpos($file, 'virgil')) {
            $actions['settings'] = '<a href="options-general.php?page=virgil_settings">Settings</a>';
        }

        return $actions;
    }

    /**
     * Validate Admin Plugin settings
     * @param $input
     * @return mixed
     */
    public function admin_options_validate($input) {

        return $input;
    }

    /**
     * Redirect after Virgil Login
     */
    public function login_redirect($redirect_to, $request_from, $user) {

        if (is_wp_error($user) && isset($_REQUEST['token'])) {

            $token = $_REQUEST['token'];
            if(!$token) {
                $error = new WP_Error('virgil_empty_token', "Virgil token was not provided correctly.");
                return $this->displayAndReturnError($error);
            }

            $this->setIncludePath();
            if (!class_exists('virgil_auth_client')) {
                require_once('core/virgil_auth_client.php' );
            }

            if(!virgil_auth_client::verify_token($token)) {
                $error = new WP_Error('virgil_wrong_token', "Virgil token was incorrect or has expired.");
                return $this->displayAndReturnError($error);
            }

            if(($userInfo = virgil_auth_client::get_user_info_by_token($token)) == false) {
                $error = new WP_Error('virgil_user_not_found', "User was not found by Virgil auth token.");
                return $this->displayAndReturnError($error);
            }

            // If everything fine, then register new user or log in.
            $userName = $userInfo['first_name'] . ' ' . $userInfo['last_name'];
            if (!username_exists($userName) and email_exists($userInfo['email']) == false) {
                $password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                wp_create_user($userName, $password, $userInfo['email']);
            }

            $user = get_user_by('email', $userInfo['email'])->to_array();

            // Update Virgil login state
            update_user_meta($user['ID'], 'virgil_last_login', date('Y-m-d H:i:s'));

            //login
            wp_set_current_user($user['ID'], $user['user_login']);
            wp_set_auth_cookie($user['ID']);
            do_action('wp_login', $user['user_login']);

            //redirect to home page after logging in (i.e. don't show content of www.site.com/?p=1234 )
            wp_redirect(home_url());
            exit;

        }

        return $redirect_to;
    }

    public function lost_password_form() {

        if(isset($_GET['email']) && $_GET['email']) {
            $user = get_user_by('email', $_GET['email']);
            if($user) {

                $options = $this->get_plugin_options();

                echo '
                <p class="virgil-login">
                    <a href="https://auth-demo.virgilsecurity.com/uploads/virgil-chrome.zip">Download Virgil Extension</a>
                    <button data-virgil-ui="auth-btn" data-virgil-reference="' . $options['redirect_url'] . '">Virgil Auth</button>
                </p>

                <p class="message virgil-message">
                    Try to log in through the Virgil Auth.
                </p>';

                wp_enqueue_script('forgot', $this->get_plugin_url() . '/js/forgot.js');
            }
        }
    }

    /**
     * Catch lost password action
     */
    public function lost_password_post() {

        if(!empty($_REQUEST['user_login']) && !isset($_REQUEST['without_virgil'])) {
            $user = get_user_by('email', $_REQUEST['user_login']);
            if($user) {
                $user = $user->to_array();
                $isVirgilUsingByUser = get_user_meta($user['ID'], 'virgil_last_login', true) ? true : false;
                if($isVirgilUsingByUser) {
                    wp_redirect(home_url() . '/wp-login.php?action=lostpassword&email=' . urlencode($_REQUEST['user_login']));
                    exit();
                }
            }
        }
    }

    /**
     * Set include path
     */
    public function setIncludePath() {
        if (!$this->doneIncludePath) {
            set_include_path(get_include_path() . PATH_SEPARATOR . plugin_dir_path(__FILE__));
            $this->doneIncludePath = true;
        }
    }

    /**
     * Get plugin base name
     * @return string
     */
    public function get_plugin_base_name() {

        $basename = plugin_basename(__FILE__);
        if ('/' . $basename == __FILE__) { // Maybe due to symlink
            $basename = basename(dirname(__FILE__)) . '/' . basename(__FILE__);
        }
        return $basename;
    }

    /**
     * Get plugin url to correctly include CSS, JSS stuff
     * @return string
     */
    protected function get_plugin_url() {

        $basename = plugin_basename(__FILE__);
        if ('/' . $basename == __FILE__) { // Maybe due to symlink
            return plugins_url() . '/' . basename(dirname(__FILE__)) . '/';
        }
        // Normal case (non symlink)
        return plugin_dir_url( __FILE__ );
    }
}

// Global accessor function to singleton
function virgil_login() {
    return virgil_login::getInstance();
}

// Initialise at least once
virgil_login();