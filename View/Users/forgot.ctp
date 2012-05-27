<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Forgot password?'); ?></span></div>
<div class="formContent">
<p><?= __('Type your email address to restore your password'); ?></p>
<?= $this->Form->create('User', array('type' => 'post', 'url' => '/forgot', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('email', array('placeholder' => __('Email'))); ?>
<?= $this->Recaptcha->display(array('recaptchaOptions' => array('theme' => 'clean'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Send', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>