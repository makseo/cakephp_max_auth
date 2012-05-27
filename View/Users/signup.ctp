<script type="text/javascript">
$(document).ready(function(){$('#UserSignupForm').validate({
rules:
{
"data[User][email]":{required:true, email:true},
"data[User][password]":{required:true, minlength:3, maxlength:100},
"data[User][password_confirm]":{required:true ,minlength:3, maxlength:100, equalTo:"#UserPassword"},
"recaptcha_response_field":{required:true}
},
onkeyup:false,errorClass:'form-error',validClass:'valid',errorPlacement:function(error,element){var elem=$(element),corners=['left center','right center'],flipIt=elem.parents('span.right').length>0;if(!error.is(':empty')){elem.filter(':not(.valid)').qtip({overwrite:false,content:error,position:{my:corners[flipIt?0:1],at:corners[flipIt?1:0],viewport:$(window)},show:{event:false,ready:true},hide:false,style:{classes:'ui-tooltip-red'}}).qtip('option','content.text',error)}else{elem.qtip('destroy')}},success:$.noop});});
</script>
<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Sign Up'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('User', array('type' => 'post', 'url' => '/signup', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('email', array('placeholder' => __('Email'))); ?>
<?= $this->Form->input('nickname', array('placeholder' => __('Nickname'))); ?>
<?= $this->Form->input('password', array('placeholder' => __('Password'))); ?>
<?= $this->Form->input('password_confirm', array('type' => 'password', 'placeholder' => __('Password confirm'))); ?>
<?= $this->Recaptcha->display(array('error' => false, 'recaptchaOptions' => array('theme' => 'clean'))); ?>
</div>
<div class="formButtons">
<?= $this->Form->submit('Sign Up', array('class' => 'button button-blue')); ?>
</div>
<?= $this->Form->end(); ?>
</div>