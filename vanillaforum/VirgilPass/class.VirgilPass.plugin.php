<?php if (!defined('APPLICATION')) exit();
session_start();
// Define the plugin:
$PluginInfo['VirgilPass'] = array(
    'Name' => 'Virgil Pass',
    'Description' => 'Virgil Pass Extension',
    'Version' => '1.0',
    'RequiredTheme' => FALSE,
    'RequiredPlugins' => FALSE,
    'MobileFriendly' => TRUE,
    'SettingsUrl' => '/dashboard/settings/VirgilPass',
    'SettingsPermission' => 'Garden.Settings.Manage',
    'HasLocale' => TRUE,
    'RegisterPermissions' => FALSE,
    'Author' => "VirgilSecurity",
    'AuthorEmail' => 'support@virgilsecurity.com',
    'AuthorUrl' => 'http://www.virgilsecurity.com/'
);

class VirgilPassPlugin extends Gdn_Plugin {

    /**
     * Before page render event
     * @param $Sender
     */
    public function Base_Render_Before($Sender) {

        $Sender->AddCssFile('plugins/VirgilPass/login.css');
        $Sender->Head->AddString('<script src="' . C('Plugins.VirgilPass.sdkUrl') . '" type="text/javascript"></script>');

    }

    /**
     * Add Virgil Pass interface at entry controller Popup.
     */
    public function EntryController_SignIn_Handler($Sender, $Args) {

        if(C('Plugins.VirgilPass.disabled') == 'no') {
            $SignInHtml='<div style="margin-top:10px">
                            <p class="virgil-login">
                                <button data-virgil-ui="auth-btn" data-virgil-reference="' . C('Plugins.VirgilPass.redirectUrl') . '" type="button">Virgil Auth</button>
                            </p>
                        </div>';

            $VirgilPassMethod = array(
                'Name' => 'VirgilLogin',
                'SignInHtml' => $SignInHtml
            );

            $Sender->Data['Methods'][] = $VirgilPassMethod;
        }
    }

    public function Base_BeforeSignInButton_Handler($Sender, $Args) {

        if(($token = GetIncomingValue('token')) !== false) {

            require 'core/virgil_auth_client.php';

            $virgil_auth_client = new virgil_auth_client(array(
                'auth_url' => C('Plugins.VirgilPass.authUrl')
            ));

            $error = false;
            if(!$virgil_auth_client->verify_token($token)) {
                $Sender->AddAsset('Messages', 'Virgil token validation failed.');
                $error = true;
            }

            if(($userInfo = $virgil_auth_client->get_user_info_by_token($token)) == false) {
                $Sender->AddAsset('Messages', 'Virgil user was not found.');
                $error = true;
            }

            if(!$error) {

            }
        }

    }

    /**
     * Plugin disable event. Clear plugin configuration options
     */
    public function onDisable() {

        RemoveFromConfig('Plugins.VirgilPass.disabled');
        RemoveFromConfig('Plugins.VirgilPass.redirectUrl');
        RemoveFromConfig('Plugins.VirgilPass.sdkUrl');
        RemoveFromConfig('Plugins.VirgilPass.authUrl');
    }

    /**
     * Plugin Setup Event. Setup default plugin options
     * @return bool|void
     */
    public function Setup() {

        $http     = ((isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://" : "http://");
        $location = $http . $_SERVER["HTTP_HOST"];

        SaveToConfig('Plugins.VirgilPass.disabled', 'no');
        SaveToConfig('Plugins.VirgilPass.redirectUrl', $location . '?token={{virgilToken}}');
        SaveToConfig('Plugins.VirgilPass.sdkUrl', 'https://auth-demo.virgilsecurity.com/js/sdk.js');
        SaveToConfig('Plugins.VirgilPass.authUrl', 'https://auth.virgilsecurity.com');
    }
}