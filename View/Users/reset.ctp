<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Password Reset'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('User', array('type' => 'post', 'url' => '/reset', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->hidden('code', array('value' => $code)); ?>
<?= $this->Form->input('password', array('placeholder' => __('New Password'))); ?>
<?= $this->Form->input('password_confirm', array('type' => 'password', 'placeholder' => __('New Password Confirm'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Change', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>