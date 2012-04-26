<div class="row-fluid">
    <div class="span10" style="position: relative;">
    	<div style="position: absolute; top: 12px; right: 5px; font-size: 25px;">
    		<a class="prev" href="#">&laquo;</a> <a class="next" href="#">&raquo;</a>
    	</div>
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th><h1 style="margin-bottom: 10px;">Monumenten</h1></th>
                </tr>
            </thead>
            <tbody id="monument_list">
            </tbody>
        </table>
    </div>  
    
    <div class="span2">
    		<h2 style="margin-top: 10px; margin-bottom: 10px;">Selectie</h2>
			<form method="post" action=""  id="filter_list" style="margin-bottom: 0;">
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
			<select id="sort">
				<option value="0">-- Sorteer</option>
				<option value="name">Op Naam</option>
			</select>
			<input type="submit" value="Filter" />
			</form>
		</div>  
</div>

