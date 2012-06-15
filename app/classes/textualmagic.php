<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * Helper for TextualMagic
 *
 * Get textually similar monuments
 * @package CultuurApp
 * @category Helpers
 * @author Rutger Plak
 */
class TextualMagic {
	/**
	 * Function to extract the category of a monument
	 *
	 * @return id of guessed category
	 */
	public static function extractCategory($monument) {
		// use the first 5 keywords of the monument
		$keywords = $monument->tags(5);
		$category = null;
		$probabilities = array();
		// calculate the maximum probability of an occurrence of a tag in a category
		foreach($keywords as $keyword) {
			$tags = DB::select('*')->from('tags')->where('content', '=', $keyword['original'])->execute();
			foreach($tags as $tag) {
				for($i = 1; $i < 15 ; $i++) {
					$probabilities[$i] =  isset($probabilities[$i]) ? $probabilities[$i] + $tag['importance'] * $tag['cat'.$i.'tfidf'] : $tag['importance'] * $tag['cat'.$i.'tfidf'];
				}
			}
		}
		$max = 0;
		$sum = 0;
		// check which category has highest probability
		foreach($probabilities as $key=>$probability) {
			if($probability>$max) {
				$category = $key;
				$max = $probability;
			}
			$sum+=$probability;
		}
		return $category;
	}

	/**
	 * Function to get related monuments based on textual analysis
	 *
	 * @param int $limit number of related monuments requested
	 * @return array with monuments
	 */
	public static function related($monument, $limit) {
		$keywords = $monument->tags(5);
		$prefix = Database::instance()->table_prefix();
		// match monuments with > 2 tags the same
		$results = DB::query(Database::SELECT,"SELECT COUNT(DISTINCT(tag.content)), monument.id_monument
				FROM {$prefix}monuments monument
				INNER JOIN {$prefix}tag_monument link
		  ON link.monument = monument.id_monument
		  INNER JOIN dev_tags tag
		  ON tag.id = link.tag
		  WHERE tag.content IN('".implode("','",$keywords)."')
		  AND monument.id_monument != ".$monument->id_monument."
		  GROUP BY monument.id_monument
		  HAVING COUNT(DISTINCT(tag.content)) >= 2
		  limit ".$limit.";",TRUE)->execute();
		// create monumentset from result
		$monuments = ORM::factory('monument');
		foreach($results as $result) {
			$monuments = $monuments->or_where('id_monument','=',$result['id_monument']);
		}
		$monuments = $monuments->find_all();
		return $monuments;
	}

	/**
	 * Function which uses TFIDF for text analysis and returns the top x most relevant tags
	 *
	 * @param int $limit number of keywords requested
	 * @return array with tags
	 */
	public static function tags($monument, $limit) {
		$prefix = Database::instance()->table_prefix();
		$tags = DB::select(
				array("tags.content", "tag"),
				array(DB::expr("({$prefix}link.occurrences * LOG(24500 / (1 + {$prefix}tags.occurrences)))"), "tfidf"),
				array("tags.id", "id"))
				->from(array("tag_monument","link"))
				->join("tags")->on("tags.id", "=", "link.tag")
				->where(DB::expr("LENGTH({$prefix}tags.content)"), ">", 4)
				->and_where("link.monument", "=", $monument->id_monument)
				->order_by("tfidf", "desc")
				->limit($limit)->execute();


		$keywords = array();

		foreach ($tags AS $keyword) {
			$keywords[] = array('original' => $keyword['tag'], 'content' => Translator::translate('tag',$keyword['id'],'tag',$keyword['tag']));
		}

		return $keywords;
	}

	/**
	 * Function which creates tagcloud with tags
	 * differing in size
	 *
	 * @param $size size of the tagcloud measured in words
	 * @return array with tags and their size
	 */
	public static function tagcloud($size) {
		// get random tags with high tfidf importance
		$limit = $size;
		$tagset = DB::select()
		->from("tags")
		->where(DB::expr("length(content)"), ">", 4)
		->where("occurrences", ">", 2)
		->where("importance", ">", 0.141430140)
		->order_by(DB::expr("RAND()"))
		->limit($limit)
		->execute();

		// convert to array
		$tags = array();
		foreach($tagset as $key=>&$tag) {
			$tag['content'] = preg_replace('/(^\-)|(\-$)/','',$tag['content']);
			$tags[$tag['importance']] = array(
					'original' => $tag['content'],
					'content' => strtolower(Translator::translate(
							'tag',
							$tag['id'],
							'tag',
							$tag['content']
					))
			);
		}

		// sort by importance and add fontsize
		ksort($tags);
		$i = 0;
		foreach($tags as $key=>&$tag) {
			$tag['fontsize'] = 12+$i;
			$i+=1;
		}
		// sort alphabetically
		asort($tags);

		return $tags;
	}

	/**
	 * Get synonyms from Thesaurus
	 * 
	 * @param string $search
	 * @return array with synonyms
	 */
	public static function synonyms($search) {
		$synonyms = DB::select(array("w2.word", "synoniem"))
		->from(
				array("thesaurus_words", "w1"))
				->join(array("thesaurus_links", "l"))->on("w1.id", "=", "l.word")
				->join(array("thesaurus_words", "w2"))->on("w2.id", "=", "l.synonym")
				->and_where("w1.word", "=", $search)->execute();

		if ($synonyms->count() == 0)
			return false;

		return $synonyms->as_array();
	}
}