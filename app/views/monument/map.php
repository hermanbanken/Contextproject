	<header class="jumbotron subhead" id="overview">
		<h1>Kaartweergave</h1>
		<p class="lead">Llorem ipsum blablablalablablablablablablabla</p>
	</header>

		
		<div style="position: absolute; top: 0; left: 100px; width: 220px; z-index: 2; padding: 20px; padding-top:60px; background: #1E1E1E;-webkit-border-radius: 20px;border-radius: 20px;">
			<form method="post" action=""  id="filter" style="margin-bottom: 0;">
			<input id="search" type="text" value="zoeken" /> 
			<input id="town" type="text" value="stad" /> 
			<select id="categories">
				<option value='-1'>-- Categorie</option>
				<?php 
				$categories = ORM::factory('category')->find_all();
				foreach($categories AS $category) {
					echo "<option value=".$category->id_category.">".$category->name."</option>";
				}
				?>
			</select><br />
			<input type="submit" value="Filter" /><img src="http://cdn3.iconfinder.com/data/icons/softwaredemo/PNG/128x128/DrawingPin1_Blue.png" width="25px" height="25px" style="margin-left:150px" id="local"/>
			</form>
		</div>
	<div id="kaart" style="position: absolute; z-index: 1; top: 40px; left: 0; width: 100%; height: 100%;"></div>