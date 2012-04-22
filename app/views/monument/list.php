<div class="row-fluid">   
    <div class="span8">
        <table class="table table-striped table-condensed">
            <thead>
                <tr>
                    
                    <th>Monumenten</th>
                    
                </tr>
                
            </thead>
            <tbody id="monument_list">
      
                <?php 
				
				/*
				
				
				
				
				
				$rutgersarray = null;
                foreach($rutgersarray as $element){
					
				echo '
                <tr>
                    <td class="span2"><div style="height:100px; overflow:hidden;">' . $element["name"] .'</div></td>
                    <td class="span5"><div style="height:100px; overflow:hidden;">' . $element["description"] . '</div></td>
                    <td class="span1"><div style="height:100px; overflow:hidden;"><img src="http://placehold.it/100x100" alt=""></div></td>
                    
                </tr>';
				*/
				?>

            </tbody>
        </table>
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
</div>

