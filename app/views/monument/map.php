
<div class="container">

    <header class="jumbotron subhead" id="overview">
            <h1>Kaartweergave</h1>
            <p class="lead">Llorem ipsum blablablalablablablablablablabla</p>
    </header>


	<div class="row"></div>
	<div class="span8" id="kaart" style="width: 700px; height: 800px;">

	</div>
	<div class="span4" id="filter">
    <h1>Selecteren</h1>
    <input id="search" type="text" value="zoeken" />
    <input id="stad" type="text" value="stad" />
    <input id="straat" type="text" value="straat" />
    <select id="categories">
    <option value='-1'>- Categorie -</option>
    <?php 
    $categorien = ORM::factory('category');
    foreach($categorien as $key=>$categorie) {
        echo "<option value=".$categorie->id.">".$categorie->description."</option>";
    }
    ?>
    </select>
    </form>

</div>


