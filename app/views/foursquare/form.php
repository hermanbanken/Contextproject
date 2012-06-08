<div class="container">
  <div class="row">
	<div class="span8">
	  <?php if(isset($suggest)) echo $suggest; ?>

	  <h1><?php echo __("foursquare.form.header"); ?></h1>
	  <form class="form-horizontal" method='post'>

		  <?php if(isset($force)) echo "<input type='hidden' name='force' value='$force' />"; ?>

		  <fieldset>
		  <legend><?php echo __("foursquare.form.legend"); ?></legend>

		  <div class="control-group <?php if(isset($errors) && isset($errors['name'])) echo 'error'; ?>">
			<label class="control-label" for="i-name"><?php echo __("foursquare.form.legend.name"); ?></label>
			<div class="controls">
			  <input type="text" class="input-large" id="i-name" name="name" value="<?php echo $venue->name; ?>" />
			  <?php if(isset($errors) && isset($errors['name'])): ?>
				<span class="help-inline"><?php echo $errors['name']; ?></span>
			  <?php endif; ?>
			  <p class="help-block"><?php echo __("foursquare.form.help.name"); ?></p>
			</div>
		  </div>

		  <div class="control-group <?php if(isset($errors) && isset($errors['city'])) echo 'error'; ?>">
			<label class="control-label" for="i-city"><?php echo __("foursquare.form.legend.city"); ?></label>
			<div class="controls">
			  <input type="text" class="input-large" id="i-city" name="city" value="<?php echo $venue->city; ?>" placeholder="<?php echo __("foursquare.form.help.city"); ?>" />
			  <?php if(isset($errors) && isset($errors['city'])): ?>
				<span class="help-inline"><?php echo $errors['city']; ?></span>
			  <?php endif; ?>
			</div>
		  </div>

		  <div class="control-group <?php if(isset($errors) && isset($errors['address'])) echo 'error'; ?>">
			<label class="control-label" for="i-addr"><?php echo __("foursquare.form.legend.address"); ?></label>
			<div class="controls">
			  <input type="text" class="input-large" id="i-addr" name="address" value="<?php echo $venue->address; ?>" placeholder="<?php echo __("foursquare.form.help.address"); ?>" />
			</div>
		  </div>

		  <div class="control-group <?php if(isset($errors) && isset($errors['description'])) echo 'error'; ?>">
			<label class="control-label" for="i-desc"><?php echo __("foursquare.form.legend.description"); ?></label>
			<div class="controls">
			  <textarea class="input-xlarge" id="i-desc" name="description" rows="3"><?php echo HTML::entities($venue->description); ?></textarea>
			  <p class="help-block"><?php echo __("foursquare.form.help.description"); ?></p>
			</div>
		  </div>

		  <div class="form-actions">
			<button type="submit" class="btn btn-primary"><?php echo __("foursquare.form.save"); ?></button>
			<a class="btn" href="javascript:history.back(1);"><?php echo __("foursquare.form.cancel"); ?></a>
		  </div>

		</fieldset>
	  </form>
	</div>
  </div>
</div>
