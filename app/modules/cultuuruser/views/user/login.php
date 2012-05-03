<?php echo Form::open() ?>
<?php if ($message): ?><?php echo $message ?><?php endif ?>
<dl>
    <dt><?php echo Form::label('username', 'Username') ?></dt>
    <dd><?php echo Form::input('username', Request::current()->post('username')) ?></dd>
 
    <dt><?php echo Form::label('password', 'Password') ?></dt>
    <dd><?php echo Form::password('password') ?></dd>

    <dt><?php echo Form::label('remember', 'Auto-login next time?') ?></dt>
    <dd><?php echo Form::checkbox('remember') ?></dd>
</dl>
 
<?php echo Form::submit(NULL, 'Login', array('class'=>'btn btn-primary')) ?>
<?php echo Form::close() ?>