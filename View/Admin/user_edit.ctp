<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Edit user account'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('User', array('type' => 'post', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('email', array('placeholder' => __('Email'))); ?>
<?= $this->Form->input('nickname', array('placeholder' => __('Nickname'))); ?>
<?= $this->Form->input('password', array('placeholder' => __('Password'))); ?>
<?= $this->Form->input('group_id', array('placeholder' => __('Group ID'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Update', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>