<div class="row-fluid">   
    <div class="span10">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    <th><h1>Monumenten</h1></th>
                </tr>
            </thead>
            <tbody id="monument_list">
            </tbody>
        </table>
    </div>  
    
    <div style="position: fixed; top:0; right: 50px; width: 220px; z-index: 2; padding: 20px; padding-top:60px; background: #1E1E1E;-webkit-border-radius: 20px;border-radius: 20px;">
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

