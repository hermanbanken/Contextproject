<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for Importing
 *
 * Import monuments, features e.d.
 * @package CultuurApp
 * @category Helpers
 * @author Sjoerd van Bekhoven
 */
class Importer {
	/**
	 * Function to import monuments from the folder "files/monuments"
	 * Files should be xml-files named {id_monument}.xml
	 * @return int aantal monumenten
	 */
	public static function monuments() {
		// Set time limit at unlimited (long import)
		set_time_limit(0);
		
		// Set counter
		$i = 0;
		
		// Make a list of id's from monument that are already stored
		$ids = array();
		$monuments_orm = ORM::factory('monument')->find_all();
		foreach ($monuments_orm AS $monument) {
			$ids[] = $monument->id_monument;
		}
		
		// Loop through all files in the map /public/files/monuments
		if ($monuments = opendir('files/monuments')) {
			while (($monument_file = readdir($monuments)) !== false) {
				// Check if file is xml-file
				if (substr($monument_file, -3) == 'xml') {
					// Set some basis arrays
					$data = array();
					$link = array();
		
					// Find id in filename
					$monument_id = intval($monument_file);
		
					// If it is an unparsed monument
					if (!in_array($monument_id, $ids)) {
		
						// Read file and parse into array
						$info = XMLParser::xml2array(@file_get_contents('files/monuments/'.$monument_id.'.xml'));
		
						// Array for the records of wikimedia in the form (name_in_file => name_in_database)
						$wikimedia_array = array('monumentNumber' => 'id_monument',
								'title' => 'name',
								'description' => 'description_commons',
								'longitude' => 'lng',
								'latitude' => 'lat',
								'usageOnOtherWikis' => 'wiki',
								'usageOnWikiMediaCommons' => 'commons');
		
						// Loop through data and save in link- or data-array
						foreach ($wikimedia_array AS $wikimedia => $field) {
							if (isset($info['Monument']['wikimediaCommons'][$wikimedia])) {
								if ($wikimedia == 'usageOnOtherWikis' || $wikimedia == 'usageOnWikiMediaCommons') {
									$link[$field] = $info['Monument']['wikimediaCommons'][$wikimedia];
		
								}
								else {
									$data[$field] = urldecode($info['Monument']['wikimediaCommons'][$wikimedia]);
								}
							}
						}
		
						// Array for the records of monumentregistry in the form (name_in_file => name_in_database)
						$mr_array = array('province' => 'id_province',
								'municipality' => 'id_municipality',
								'town' => 'id_town',
								'street' => 'id_street',
								'streetNumber' => 'streetNumber',
								'zipCode' => 'zipCode',
								'mainCategory' => 'id_category',
								'subCategory' => 'id_subcategory',
								'function' => 'id_function',
								'description' => 'description');
		
						// Loop through data and save in link- or data-array
						foreach ($mr_array AS $mr => $field) {
							if (isset($info['Monument']['monumentRegistry'][$mr])) {
								if (is_array($info['Monument']['monumentRegistry'][$mr])) {
									$link[$field] = $info['Monument']['monumentRegistry'][$mr];
								}
								else {
									$data[$field] = urldecode($info['Monument']['monumentRegistry'][$mr]);
								}
							}
						}
		
						// If category is present, find id or insert new category in database
						if (isset($data['id_category'])) {
							$category = ORM::factory('category')->where('name', '=', $data['id_category'])->find();
							if ($category->name == NULL) {
								$category->name = $data['id_category'];
								try {
									$category->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['id_category'] = $category->id_category;
		
							// If subcategory is present, find id or insert new category in database
							if (isset($data['id_subcategory'])) {
								$subcategory = ORM::factory('subcategory')->where('name', '=', $data['id_subcategory'])->find();
								if ($subcategory->name == NULL) {
									$subcategory->name = $data['id_subcategory'];
									$subcategory->id_category = $category->id_category;
									try {
										$subcategory->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['id_subcategory'] = $subcategory->id_subcategory;
							}
						}
		
						// If province is present, find id or insert new category in database
						if (isset($data['province'])) {
							$province = ORM::factory('province')->where('name', '=', $data['province'])->find();
							if ($province->name == NULL) {
								$province->name = $data['province'];
								try {
									$province->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['province'] = $province->id_province;
		
							// If municipality is present, find id or insert new category in database
							if (isset($data['municipality'])) {
								$municipality = ORM::factory('municipality')->where('name', '=', $data['municipality'])->find();
								if ($municipality->name == NULL) {
									$municipality->name = $data['municipality'];
									$municipality->id_province = $province->id_province;
									try {
										$municipality->save();
									}
									catch (Exception $e) {
										echo $e->getMessage().'<br />';
									}
								}
								$data['municipality'] = $municipality->id_municipality;
		
								// If town is present, find id or insert new category in database
								if (isset($data['town'])) {
									$town = ORM::factory('town')->where('name', '=', $data['town'])->find();
									if ($town->name == NULL) {
										$town->name = $data['town'];
										$town->id_municipality = $municipality->id_municipality;
										try {
											$town->save();
										}
										catch (Exception $e) {
											echo $e->getMessage().'<br />';
										}
									}
									$data['town'] = $town->id_town;
		
									// If street is present, find id or insert new category in database
									if (isset($data['street'])) {
										$street = ORM::factory('street')->where('name', '=', $data['street'])->and_where('id_town', '=', $town->id_town)->find();
										if ($street->name == NULL) {
											$street->name = $data['street'];
											$street->id_town = $town->id_town;
											try {
												$street->save();
											}
											catch (Exception $e) {
												echo $e->getMessage().'<br />';
											}
										}
										$data['street'] = $street->id_street;
									}
								}
							}
						}
		
						// If function is present, find id or insert new category in database
						if (isset($data['function'])) {
							$function = ORM::factory('function')->where('name', '=', $data['function'])->find();
							if ($function->name == NULL) {
								$function->name = $data['function'];
								try {
									$function->save();
								}
								catch (Exception $e) {
									echo $e->getMessage().'<br />';
								}
							}
							$data['function'] = $function->id_function;
						}
		
						// Insert new monument in database
						$monument = ORM::factory('monument')->where('id_monument', '=', $data['id_monument'])->find();
						if ($monument->id_monument == NULL) {
							foreach ($data AS $key => $value) {
								$monument->$key = $value;
							}
							try {
								$monument->save();
							}
							catch (Exception $e) {
								echo $e->getMessage().'<br />';
							}
						}
		
						// Insert links in database
						foreach ($link AS $key => $url) {
							// Use recursive function (url depth is unknonw, sometimes 5 nodes)
							Importer::monuments_link($monument->id_monument, $url, $key);
						}
		
						$i++;
					}
				}
			}
		}
		
		closedir($monuments);
		
		return $i;
	}
	
	/**
	 * Recursive function to save links belonging to monuments because
	 * the depth of the url array is unknown and sometimes more than 6,7
	 * @param int $id_monument
	 * @param string $url
	 * @param string $name
	 */
	public static function monuments_link($id_monument, $url, $name) {
		if (is_array($url)) {
			foreach ($url AS $value) {
				Importer::monuments_link($id_monument, $value, $name);
			}
		}
		else {
			// Look if link is already in database, otherwise insert new one
			$link = ORM::factory('link')->where('url', '=', $url)->find();
			if ($link->url == NULL) {
				$link->url = $url;
				$link->name = $name;
				try {
					$link->save();
				}
				catch (Exception $e) {
					echo $e->getMessage().'<br />';
				}
			}
	
			// Connect the inserted link to the monument it belongs to if not already done
			$links = DB::select()->from('monument_link')
			->where('id_monument', '=', $id_monument)
			->and_where('id_link', '=', $link->id_link)
			->execute();
			if ($links->count() == 0) {
				DB::insert("monument_link", array(intval($id_monument), intval($link->id_link)))->execute();
			}
		}
	}
}
?>