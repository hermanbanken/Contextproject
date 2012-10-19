<ul class="thumbnails">
<?php
  $monuments = Recommender::recommend(4);
  foreach ($monuments['monuments'] AS $monument) {
  	echo '
    <li class="span3">
      <a href="'.URL::site('monument/id/'.$monument->id_monument).'" class="thumbnail">
        <img src="'.$monument->photoUrl().'" style="max-width: 260px; max-height: 180px;" alt="">
      </a>
    </li>';
  }
?>
</ul>