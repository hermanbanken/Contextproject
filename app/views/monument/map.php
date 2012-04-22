<div class="container">

	<header class="jumbotron subhead" id="overview">
		<h1>Kaartweergave</h1>
		<p class="lead">Llorem ipsum blablablalablablablablablablabla</p>
	</header>

	<div class="span7" id="kaart" style="height: 800px;">

	</div>
	
	<div class="span4" id="filter">
		<h1>Selecteren</h1>
		<input id="search" type="text" value="zoeken" /> <input id="stad"
			type="text" value="stad" /> <input id="straat" type="text"
			value="straat" /> <select id="categories">
			<option value='-1'>- Categorie -</option>
			<?php 
			$categorien = ORM::factory('category')->find_all();
			foreach($categorien as $key=>$categorie) {
				echo "<option value=".$categorie->id_category.">".$categorie->name."</option>";
			}
			?>
		</select>
		</form>
	</div>

</div>
