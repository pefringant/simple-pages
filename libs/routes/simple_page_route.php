<?php
class SimplePageRoute extends CakeRoute {
/**
 * Custom Route parsing for SimplePages URL
 * 
 * @param string $url URL to parse
 */
	function parse($url) {
		$params = parent::parse($url);
		
		if (empty($params)) {
			return false;
		}
		
		$slugs = Cache::read('simple_page_slugs');
		
		if (empty($slugs)) {
			App::import('Model', 'SimplePages.SimplePage');
			$SimplePage = new SimplePage();
			$slugs = $SimplePage->cacheSlugs();
		}
		
		if (isset($params['slug']) && in_array($params['slug'], $slugs)) {
			return $params;
		}
		
		return false;
	}
}