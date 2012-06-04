<div class='modal form-login hide fade'>
	<div class="modal-header">
	    <a class="close" data-dismiss="modal">×</a>
	    <h3><?php echo __('login.login-to-cultuurapp'); ?></h3>
	</div>
	<div class="modal-body">
	<?php 	
		echo Form::open(URL::site('user/login'));
		if ($message){
			echo $message;
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
				<a class="btn btn-small btn-primary" href="<?php echo URL::site("user/provider/facebook"); ?>">F</a>
				<a class="btn btn-small btn-primary" href="<?php echo URL::site("user/provider/facebook"); ?>"><?php echo __('login.with-fb'); ?></a>
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