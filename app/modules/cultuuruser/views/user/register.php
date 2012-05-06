<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3>Register at CultuurApp.nl</h3>
	</div>
	<div class="modal-body">
		<?php echo Form::open() ?>
		<?php if ($errors): ?>
		<p class="message">Some errors were encountered, please check the details you entered.</p>
		<ul class="errors">
		<?php foreach ($errors as $message): ?>
		    <li><?php echo $message ?></li>
		<?php endforeach ?>
		</ul>
		<?php endif ?>
		
		<?php echo Form::input('username', Request::current()->post('username'), array('placeholder'=>'Username')) ?>
		<?php echo Form::input('email', Request::current()->post('email'), array('placeholder'=>'Email')) ?>
		<?php echo Form::password('password', null, array('placeholder'=>'Password')) ?>
		<?php echo Form::password('password_confirm', null, array('placeholder'=>'Confirm password')) ?>
		<p class="help-block">Passwords must be at least 8 characters long.</p>
		
		<?php echo Form::submit(NULL, 'Sign up', array('class'=>'btn btn-primary')) ?>
		<?php echo Form::close() ?>
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- or --</p>
		<div class="bs-links">
			<p><a href="user/login">Login</a> if you already have an account.</p>
			<div class="btn-group">
				<a class="btn btn-small btn-primary" href="user/provider/facebook">F</a>
				<a class="btn btn-small btn-primary" href="user/provider/facebook">Login with Facebook</a>
			</div>
			<div class="btn-group">
				<a class="btn btn-small btn-info">T</a>
				<a class="btn btn-small btn-info">Login with Twitter</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){ 
		$('.form-login').modal();
	});
</script>