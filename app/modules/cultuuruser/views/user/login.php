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
	
		echo Form::input('username', Request::current()->post('username'), array('placeholder'=>"Username")); 
		echo Form::password('password', array('placeholder'=>"Password"));
	
		echo Form::submit(NULL, 'Login', array('class'=>'btn btn-primary', 'style'=>'margin-bottom:.5em'));
		
		echo Form::label('remember', Form::checkbox('remember').'Auto-login next time?', array('class'=>'help-block'));
	
		echo Form::close();
	?>
		
	</div>
  	<div class="modal-footer">
		<p class="modal-footer-legend">-- or --</p>
		<div class="bs-links">
			<p><a href="user/register">Register</a> if you don't have an account.</p>
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