<?php
class SimplePage extends SimplePageAppModel {
	var $name = 'SimplePage';
	
	var $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'titleNotEmpty'
		),
		'slug' => array(
			'rule' => 'notEmpty',
			'on' => 'update',
			'message' => 'slugNotEmptyOnUpdate'
		),
	);
	
	var $actsAs = array(
		'Sluggable.Sluggable' => array(
			'label' => 'title',
			'length' => 255,
			'translation' => 'utf-8'
		)
	);
	
/**
 * Finds all slugs and saves them to Cache
 * 
 * @return all slugs in an array
 */
	function cacheSlugs() {
		Cache::delete('simple_page_slugs');
 
		$data = $this->find('all', array('fields' => 'slug'));
 
		$slugs = Set::extract('/SimplePage/slug', $data);
 
		Cache::write('simple_page_slugs', $slugs);
 
		return $slugs;
	}
 
/**
 * afterSave callback : clears the slugs cache and recreates it
 * 
 * @param bool $created True if record created, false if updated
 */
	function afterSave($created) {
		parent::afterSave($created);
		$this->cacheSlugs();
	}
 
/**
 * afterDelete callback : clears the slugs cache and recreates it
 */
	function afterDelete() {
		parent::afterDelete();
		$this->cacheSlugs();
	}
}