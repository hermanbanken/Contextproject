<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3>Login to CultuurApp.nl</h3>
	</div>
	<div class="modal-body">
	<?php 	
		echo Form::open('user/login');
		if ($message){
			echo $message;
		}
	
		echo Form::input('username', Request::current()->post('username'), array('placeholder'=>__("Username"))); 
		echo Form::password('password', array('placeholder'=>__("Password")));
	
		echo Form::submit(NULL, __('Login'), array('class'=>'btn btn-primary', 'style'=>'margin-bottom:.5em'));
		
		echo Form::label('remember', Form::checkbox('remember').' '.__('Auto-login next time?'), array('class'=>'help-block'));
	
		echo Form::close();
	?>
		
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- <?php echo __('or'); ?> --</p>
		<div class="bs-links">
			<p><a href="user/register"><?php echo __('Register'); ?></a> <?php echo __("if you don't have an account."); ?></p>
			<div class="btn-group">
				<a class="btn btn-small btn-primary" href="user/provider/facebook">F</a>
				<a class="btn btn-small btn-primary" href="user/provider/facebook"><?php echo __('Login with Facebook'); ?></a>
			</div>
			<div class="btn-group">
				<a class="btn btn-small btn-info">T</a>
				<a class="btn btn-small btn-info"><?php echo __('Login with Twitter'); ?></a>
			</div>
		</div>
	</div>
</div>

<script>
	$(function(){ 
		$('.form-login').modal();
	});
</script>