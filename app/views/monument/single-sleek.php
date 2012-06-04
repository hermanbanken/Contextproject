<div class="container-fluid monument-single">
  <div class="row-fluid" style="margin-bottom: 20px;">

    <div class="span8 monument-single-overview">

      <h1><?php echo $monument->name; ?><small style="float:right"><a href="javascript:history.back(1);">Terug</a></small></h1>
      <p>
        <div class="thumbnail span4" style="float:right">
          <img src="<?php echo $monument->photoUrl(); ?>" alt="<?php echo $monument->name; ?>">
          <div class="caption">
            <h5><?php echo $monument->name; ?></h5>
            <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
            <p>
              <a class="btn" href="monument/visualcomparison/<?php echo $monument->id_monument; ?>">Vergelijk visueel</a>
              <?php
              if ($user) {
                $visited = in_array($monument->id_monument, $user->visited_monument_ids()); ?>
                <a style="margin-top: 10px;" class="btn <?php echo ($visited ? 'btn-success ' : ''); ?>visited"
                   href="#"><i
                    class="icon-ok <?php echo ($visited ? 'icon-white ' : ''); ?>"></i> <span class="text"><?php echo ($visited ? __('single.visited') : __('single.not-visited')); ?></span>
                </a>
                <?php
              }
              ?>
            </p>
          </div>
        </div>
        <?php echo str_replace("\n\n", "</p><p>", $monument->description); ?>
      </p>

    </div>

    <div class="span4 monument-single-details">

      <h2>Details</h2>
      <div class="column">
        <div class="map-outer">
          <div class="map well" id="single-map" data-map="<?php echo $monument->lat . ";" . $monument->lng; ?>">
            <script type="text/javascript">
              var m = document.getElementById("single-map"),
                  c = m.dataset['map'].split(";"),
                  p = new google.maps.LatLng(c[1], c[0]);

              var options = {
                center : p,
                zoom : 12,
                mapTypeControl : false,
                streetViewControl : false,
                mapTypeId : google.maps.MapTypeId.ROADMAP
              };
              new google.maps.Marker({ position : p, map : new google.maps.Map(m, options) });
            </script>
          </div>
        </div>
        <table class="table table-bordered table-striped">
          <tr>
            <th><?php echo __('address'); ?></th>
            <td><?php echo $monument->street->name.' '.$monument->streetNumber; ?></td>
          </tr>
          <tr>
            <th><?php echo __('city'); ?></th>
            <td><?php echo $monument->town->name; ?></td>
          </tr>
          <tr>
            <th><?php echo __('municipality'); ?></th>
            <td><?php echo $monument->municipality->name; ?></td>
          </tr>
          <tr>
            <th><?php echo __('province'); ?></th>
            <td><?php echo $monument->province->name; ?></td>
          </tr>
          <tr>
            <th><?php echo __('category'); ?></th>
            <td><?php echo $monument->category->name; ?></td>
          </tr>
          <tr>
            <th><?php echo __('subcategory'); ?></th>
            <td><?php echo $monument->subcategory->name; ?></td>
          </tr>
          <tr>
            <th>Tags</th>
            <td><?php echo strtolower(implode(', ', $monument->tags())); ?>
            </td>
          </tr>
          <tr>
            <th>FourSquare</th>
            <td><?php
              $venue = $monument->venue();
              if(isset($venue) && isset($venue->id))
              {
                echo sprintf(
                  "<a href='%s' title='%s'>%s</a>",
                  "https://foursquare.com/v/".$venue->id,
                  __("foursquare.outboundlink"),
                  __("foursquare.checkins", array(":d" => (int) $monument->venue()->checkinsCount))
                );
              } else {
                echo sprintf(
                  "<a href='%s' title='%s'>%s</a>",
                  URL::site('4sq/create/'.$monument->id_monument),
                  __("foursquare.venue.create"),
                  __("foursquare.venue.create")
                );
              }
               ?></td>
          </tr>
        </table>

        <div class="forecast">
          <div class="inner well">
<?php
foreach ($forecasts AS $forecast) {
?>
		<div class="span1 day">
			<i><img src="<?php echo $forecast->icon; ?>" alt="" /></i>
			<span class="temperature"><?php echo $forecast->temperature(); ?> &deg;C</span>
			<span class="dayabbr"><?php echo $forecast->day(); ?></span>
		</div>
<?php
}
?>
          </div>
        </div>
      </div>

    </div>

  </div>
  <ul class="nav nav-tabs single-nav" style="margin-top: 20px;">
    <li><a class="aanbevelingen"
           href="monument/id/<?php echo $monument->id_monument; ?>#aanbevelingen"><?php echo __('single.recommendations'); ?>
    </a></li>
    <li><a class="locatie"
           href="monument/id/<?php echo $monument->id_monument; ?>#locatie"><?php echo __('single.location'); ?>
    </a></li>
    <li><a class="forecast"
           href="monument/id/<?php echo $monument->id_monument; ?>#forecast"><?php echo __('single.forecast'); ?>
    </a></li>
    <li><a class="restaurants"
           href="monument/id/<?php echo $monument->id_monument; ?>#restaurants"><?php echo __('single.restaurants'); ?>
    </a></li>
    <li><a class="cafes"
           href="monument/id/<?php echo $monument->id_monument; ?>#cafes"><?php echo __('single.bars'); ?>
    </a></li>
    <li style="float: right"><img
        src="https://developers.google.com/maps/documentation/places/images/powered-by-google-on-white.png"
        alt="Powered by Google" style="background: none; border: none;" /></li>
  </ul>

  <input id="id_monument" type="hidden"
         value="<?php echo $monument->id_monument; ?>" />

  <div id="ajax_content"></div>
</div>
</div>