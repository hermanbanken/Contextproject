<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3><?php echo __('login.login-to-cultuurapp'); ?></h3>
	</div>
	<div class="modal-body">
	<?php
		echo Form::open(URL::site('user/login'));

		$messages = Message::pull();
		foreach($messages as $m){
			echo "<div class='alert alert-$m[type]'>$m[message]</div>";
		}
		if ($message){
			echo "<div class='alert alert-error'>$message</div>";
		}

		echo Form::input('username', Request::current()->post('username'), array('placeholder'=>__("login.username"))); 
		echo Form::password('password', array('placeholder'=>__("login.password")));
	
		echo Form::submit(NULL, __('login.login'), array('class'=>'btn btn-primary', 'style'=>'margin-bottom:.5em'));
		
		echo Form::label('remember', Form::checkbox('remember').' '.__('login.auto-login'), array('class'=>'help-block'));
	
		echo Form::close();
	?>
		
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- <?php echo __('login.or'); ?> --</p>
		<div class="bs-links">
			<p><a href="<?php echo URL::site("user/register"); ?>"><?php echo __('login.register'); ?></a> <?php echo __("login.no-account"); ?></p>
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