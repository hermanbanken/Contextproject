<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Flickr
 *
 * Get flickr photos surroudning a location.
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Flickr {
	/**
	 * Constants
	 */
	const KEY = 'e0ee3695155f9647a3d645aa6d3c0d2a';
	const MAX_CACHE = 10;

	/**
	 * Function to get flickrphotos from neighbourhood of monument
	 *
	 * @param monument $monument
	 * @param int $limit
	 * @return array with flickrphoto objects $flickrphotos_orm
	 */
	public static function photos($monument, $limit) {
		// Get cached flickrphotos (only flickrphotos that are not too old, max is 10 days)
		$flickrphotos_orm_query = ORM::factory('flickrphoto')
		->where('id_monument', '=', $monument->id_monument)
		->and_where('cachedOn', '>', date('Y-m-d H:i:s', mktime(0, 0, 0, date('n'), date('j'), date('Y')) - self::MAX_CACHE * 24 * 60 * 60))
		->limit($limit);

		// Execute query (reset = false, so we can use it at later time)
		$flickrphotos_orm = $flickrphotos_orm_query->reset(false)->find_all();
		
		// Check if there are cached forecasts
		if ($flickrphotos_orm->count() < $limit) {
			// Clear cache
			DB::delete('flickrphotos')->where('id_monument', '=', $monument->id_monument)->execute();
			
			// Import new photos
			Flickr::import($monument, $limit);

			// Get flickrphotos (use old query)
			$flickrphotos_orm = $flickrphotos_orm_query->reset(false)->find_all();
		}

		return $flickrphotos_orm;
	}
	
	/**
	 * Import photos from Flickr
	 * 
	 * @param limit $limit
	 */
	public static function import($monument, $limit) {
		// Request url
		$base_url = 'http://api.flickr.com/services/rest/?method=flickr.photos.search&extras=tags&format=json&api_key=%s&lat=%s&lon=%s&tags=%s';
		$url = sprintf($base_url, self::KEY, $monument->lng, $monument->lat, $monument->town->name);
		
		// Get contents of request
		$request = Request::factory($url)->execute();
		
		// Remove strange jsonflickr thingy
		$request = str_replace('jsonFlickrApi(', '', $request);
		$request = substr($request, 0, -1);
		
		// Json decode
		$request = json_decode($request);
		$request = $request->photos->photo;
			
		// Save flickrphotos to database
		$i = 0;
		foreach ($request AS $photo) {
			$flickrphoto_orm = ORM::factory('flickrphoto');
			$flickrphoto_orm->id_monument = $monument->id_monument;
			$flickrphoto_orm->url = self::url($photo);
			$flickrphoto_orm->save();
		
			$i++;
		
			if ($i == $limit) break;
		}
	}

	/**
	 * Translate flickr-photo-object to url
	 *
	 * @param flickr-photo $photo
	 * @return string $url
	 */
	public static function url($photo) {
		$base_url = 'http://farm%s.staticflickr.com/%s/%s_%s.jpg';
		$url = sprintf($base_url, $photo->farm, $photo->server, $photo->id, $photo->secret);

		return $url;
	}
}