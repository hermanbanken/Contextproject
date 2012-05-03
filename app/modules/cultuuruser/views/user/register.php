<?php echo Form::open() ?>
<?php if ($errors): ?>
<p class="message">Some errors were encountered, please check the details you entered.</p>
<ul class="errors">
<?php foreach ($errors as $message): ?>
    <li><?php echo $message ?></li>
<?php endforeach ?>
</ul>
<?php endif ?>
<dl>
    <dt><?php echo Form::label('username', 'Username') ?></dt>
    <dd><?php echo Form::input('username', Request::current()->post('username')) ?></dd>
 
    <dt><?php echo Form::label('password', 'Password') ?></dt>
    <dd><?php echo Form::password('password') ?></dd>
    <dd class="help">Passwords must be at least 8 characters long.</dd>
    <dt><?php echo Form::label('confirm', 'Confirm Password') ?></dt>
    <dd><?php echo Form::password('password_confirm') ?></dd>

    <dt><?php echo Form::label('email', 'Email') ?></dt>
    <dd><?php echo Form::input('email', Request::current()->post('email')) ?></dd>
</dl>
 
<?php echo Form::submit(NULL, 'Sign Up', array('class'=>'btn btn-primary')) ?>
<?php echo Form::close() ?>