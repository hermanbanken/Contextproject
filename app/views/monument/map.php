<div id="searchdiv">
	<form method="post" action=""  id="filter" style="margin-bottom: 0;">
		<input id="search" type="text" placeholder="zoeken" /> 
		<input id="town" type="text" placeholder="stad" /> 
		<select id="categories">
			<option value='-1'>-- Categorie</option>
			<?php 
				$categories = ORM::factory('category')->where('id_category', '!=', 3)->order_by('name')->find_all();
				foreach($categories AS $category) {
					echo "<option value=".$category->id_category.">".$category->name."</option>";
				}
				?>
		</select>
		<br />
		<label style="color:white" for="nearby">
			<input type="checkbox" id="nearby" style="float:left"/> In de buurt zoeken
		</label>
		<div id="distancecontainer" style="display:none">
			<span style="color:white">Afstand vanaf huidige locatie</span>
			<div id="distance"></div>
      			<span id="distanceindicator" style="color:white">2 kilometer</span>
		</div>
		<input type="submit" id="filter" value="Filter" />
	</form>
</div>
<div id="kaart"></div>
