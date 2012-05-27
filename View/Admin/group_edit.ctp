<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Edit group'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('Group', array('type' => 'post', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('name', array('placeholder' => __('name'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Update', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>