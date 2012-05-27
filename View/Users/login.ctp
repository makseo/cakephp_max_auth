<div class="formBox">
<div class="formTitleBar"><span class="formTitle"><?= __('Log in or create an account'); ?></span></div>
<div class="formContent">
<?= $this->Form->create('User', array('type' => 'post', 'url' => '/login', 'inputDefaults' => array('label' => false, 'div' => false))); ?>
<?= $this->Form->input('email', array('placeholder' => __('Email'))); ?>
<?= $this->Form->input('password', array('placeholder' => __('Password'))); ?>
</div>
<div class="formButtons">
<div id="forgotPasswordLink"><a href="/forgot">Forgot password?</a></div>
<div id="rightButtons">
<a href="/signup" class="button button-gray">Signup Now</a>
<?= $this->Form->submit('Login', array('class' => 'button button-blue', 'div' => false)); ?>
<?= $this->Form->button('Facebook', array('class' => 'button button-facebook')); ?>
<script type="text/javascript">$('.button-facebook').bind('click',function(e){var fbpopupWidth=550,fbpopupHeight=350,fbpopupTop,fbpopupLeft;if(window.screenLeft==undefined){var wsl=window.screenX;var wst=window.screenY}else{var wsl=window.screenLeft;var wst=window.screenTop}fbpopupLeft=(wsl+($(window).width()-fbpopupWidth)/2);fbpopupTop=(wst+($(window).height()-fbpopupHeight)/2);e.preventDefault();window.open("<?= 'http://'.$_SERVER['SERVER_NAME'].'/facebook_login'; ?>","_blank","location=no,width=550,height=350,left=' + fbpopupLeft + ',top=' + fbpopupTop")});</script>
</div>
</div>
<?= $this->Form->end(); ?>
</div>