<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3><?php echo __('register.register-to-cultuurapp'); ?></h3>
	</div>
	<div class="modal-body">
		<?php echo Form::open() ?>
		<?php if ($errors): ?>
		<p class="message"><?php echo __('register.error'); ?></p>
		<ul class="errors">
		<?php foreach ($errors as $message): ?>
		    <li><?php echo $message ?></li>
		<?php endforeach ?>
		</ul>
		<?php endif ?>
		
		<?php echo Form::input('username', Request::current()->post('username'), array('placeholder'=>__('login.username'))) ?>
		<?php echo Form::input('email', Request::current()->post('email'), array('placeholder'=>__('register.email'))) ?>
		<?php echo Form::password('password', null, array('placeholder'=>__('login.password'))) ?>
		<?php echo Form::password('password_confirm', null, array('placeholder'=>__('register.password-confirm'))) ?>
		<p class="help-block"><?php echo __('register.error-password'); ?></p>
		
		<?php echo Form::submit(NULL, __('register.signup'), array('class'=>'btn btn-primary')) ?>
		<?php echo Form::close() ?>
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- <?php echo __('login.or'); ?> --</p>
		<div class="bs-links">
			<p><a href="user/register"><?php echo __('login.register'); ?></a> <?php echo __("login.no-account"); ?></p>
			<div class="btn-group">
				<a class="btn btn-small btn-primary" href="user/provider/facebook">F</a>
				<a class="btn btn-small btn-primary" href="user/provider/facebook"><?php echo __('login.with-fb'); ?></a>
			</div>
			<div class="btn-group">
				<a class="btn btn-small btn-info">T</a>
				<a class="btn btn-small btn-info"><?php echo __('login.with-twitter'); ?></a>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){ 
		$('.form-login').modal();
	});
</script>