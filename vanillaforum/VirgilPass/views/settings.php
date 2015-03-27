<?php if (!defined('APPLICATION')) exit(); ?>

<?php
    echo '<link rel="stylesheet" type="text/css" href="', Asset('plugins/VirgilPass/views/setting_style.css'),'" />';
?>

<div style="float:left;">
    <?php
        echo $this->Form->Open();
        echo $this->Form->Errors();
    ?>
</div>
<div>
    <div class="container">
        <div class="contentblock">
            <h4>Virgil Pass Plugin Settings</h4>
            <div>
                <div class="clearfix">
                    <label for="Form_disabled">Enable Virgil Pass?</label>
                    <?=$this->Form->RadioList('disabled', array(
                        'no' => 'Enable <strong>(Default)</strong>',
                        'yes' => 'Disable'
                    ), array('default' => $this->Form->GetFormValue('disabled')));?>
                </div>
                <div>
                    <p class="desc">Allows you to temporarily disable Virgil Pass without having to remove it.</p>
                </div>
            </div>
            <div>
                <label for="Form_redirectUrl">Redirect URL:</label>
                <?php echo $this->Form->TextBox('redirectUrl',array('maxlength' => 255, 'value' => $this->Form->GetFormValue('redirectUrl')));?>
                <p class="desc">URL where the system is redirected after successful authentication</p>
            </div>
            <div>
                <label for="Form_sdkUrl">Virgil SDK URL:</label>
                <?php echo $this->Form->TextBox('sdkUrl',array('maxlength' => 255, 'value' => $this->Form->GetFormValue('sdkUrl')));?>
                <p class="desc">Virgil JavaScript SDK URL</p>
            </div>
            <div>
                <label for="Form_authUrl">Virgil Auth URL:</label>
                <?php echo $this->Form->TextBox('authUrl',array('maxlength' => 255, 'value' => $this->Form->GetFormValue('authUrl')));?>
                <p class="desc">Virgil Authentication service URL</p>
            </div>
            <div class="clearfix">
                <?php echo $this->Form->Button('Save Changes', array('class' => 'Button SliceSubmit')); ?>
            </div>
        </div>
    </div>
</div>