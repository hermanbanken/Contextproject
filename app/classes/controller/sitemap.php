<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_Sitemap extends Controller {

	/**
	 * Make a table in the database for each monument that has a schema inside.
	 */
	public function action_index()
	{
		$cache = Cache::instance('file')->get('sitemap.xml', false);
		$this->response->headers("Content-type", "text/xml");

		if($cache)
		{
			$this->response->body($cache);
		} else {
			$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
			$xml.= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">";
			$url = "<url><loc>".URL::base('http')."%s</loc><changefreq>%s</changefreq></url>";

			$base = array(
				"",
				"monument/map",
				"monument/list",
				"?lang=nl",
				"?lang=en",
				"user/register",
			);

			// All basic pages
			foreach($base as $link){
				$xml .= sprintf($url, $link, "weekly");
			}

			// All town indexes
			for($i = ord("A"); $i <= ord("Z"); $i++){
				$xml .= sprintf($url, "monument/town/".chr($i), "monthly");
			}

			// All monuments
			$monuments = DB::select("id_monument")->from("monuments")->execute();
			foreach($monuments as $m){
				$xml .= sprintf($url, "monument/id/".$m['id_monument'], "monthly");
			}

			$xml.= "</urlset>";

			Cache::instance('file')->set('sitemap.xml', $xml);
			$this->response->body($xml);
		}
	}
}
