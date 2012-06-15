<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3><?php echo __('register.register-to-cultuurapp'); ?></h3>
	</div>
	<div class="modal-body">
	<?php
		echo Form::open();

		$messages = Message::pull();
		foreach($messages as $m){
			echo "<div class='alert alert-$m[type]'>$m[message]</div>";
		}
		if ($errors){
			echo "<p class='message'>".__('register.error')."</p>";
			foreach($errors as $message){
				echo "<div class='alert alert-error'>$message</div>";
			}
		}

		echo Form::input('username', Request::current()->post('username'), array('placeholder'=>__('login.username')));
		echo Form::input('email', Request::current()->post('email'), array('placeholder'=>__('register.email')));
		echo Form::password('password', null, array('placeholder'=>__('login.password')));
		echo Form::password('password_confirm', null, array('placeholder'=>__('register.password-confirm')));
		echo "<p class='help-block'>".__('register.error-password')."</p>";

		echo Form::submit(NULL, __('register.signup'), array('class'=>'btn btn-primary'));
		echo Form::close();
	?>
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- <?php echo __('login.or'); ?> --</p>
		<div class="bs-links">
			<p><a href="user/register"><?php echo __('login.register'); ?></a> <?php echo __("login.no-account"); ?></p>
			<div class="btn-group">
				<?php echo HTML::anchor("user/provider/facebook", "F", array("class"=> "btn btn-small btn-primary"))?>
				<?php echo HTML::anchor("user/provider/facebook", __('login.with-fb'), array("class"=> "btn btn-small btn-primary"))?>
			</div>
			<div class="btn-group">
				<?php echo HTML::anchor("user/provider/twitter", "T", array("class"=> "btn btn-small btn-info"))?>
				<?php echo HTML::anchor("user/provider/twitter", __('login.with-twitter'), array("class"=> "btn btn-small btn-info"))?>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){ 
		$('.form-login').modal();
	});
</script>