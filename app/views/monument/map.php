	<header class="jumbotron subhead" id="overview">
		<h1>Kaartweergave</h1>
		<p class="lead">Llorem ipsum blablablalablablablablablablabla</p>
	</header>

	<div class="span8" id="kaart" style="margin:0; 0;height: 800px;">

	</div>
	
	<div class="span4" id="filter">
		<h1>Selecteren</h1>
		<input id="search" type="text" value="zoeken" /> 
		<input id="stad" type="text" value="stad" /> 
		<input id="straat" type="text" value="straat" /> 
		<select id="categories">
			<option value='-1'>-- Categorie</option>
			<?php 
			$categories = ORM::factory('category')->find_all();
			foreach($categories AS $category) {
				echo "<option value=".$category->id_category.">".$category->name."</option>";
			}
			?>
		</select>
		</form>
	</div>