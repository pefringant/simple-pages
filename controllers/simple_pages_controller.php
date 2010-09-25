<?php
class SimplePagesController extends SimplePagesAppController {
	var $name = 'SimplePages';
	
	var $paginate = array(
		'order' => 'SimplePage.created DESC',
		'limit' => 10
	);

/**
 * If you use setFlash layouts to customize messages, define the following somewhere
 * in your app (e.g. config/bootstrap.php) :
 * 
 * Configure::write('SimplePages.flashLayouts', array(
 *	'success' => 'xxx',
 *	'notice' => 'yyy',
 *	'error' => 'zzz',
 * ));
 * 
 * @link http://book.cakephp.org/view/1313/setFlash
 * @var array Success, notice and error layouts for flash messages
 */
	var $flashLayouts = array(
		'success' => 'default',
		'notice' => 'default',
		'error' => 'default'
	);
	
/**
 * Checks for the key 'SimplePages.flashLayouts' in the Configure class 
 */
	function beforeFilter() {
		if ($flashLayouts = Configure::read('SimplePages.flashLayouts')) {
			$this->flashLayouts = array_merge($this->flashLayouts, $flashLayouts);
		}
		parent::beforeFilter();
	}
	
/**
 * View
 * 
 * @param string $slug Slug of the page
 */
	function view($slug = null) {
		$data = $this->SimplePage->findBySlug($slug);
		
		if (empty($data)) {
			$this->cakeError('error404');
		}
		
		if (isset($this->params['requested'])) {
			return $data;
		}
		
		$this->set(compact('data'));
	}
	
/**
 * Index
 */
	function index() {
		$data = $this->paginate();
		
		if (isset($this->params['requested'])) {
			return $data;
		}
		
		$this->set('data', $data);
	}
	
/**
 * Admin::index
 *
 */
	function admin_index() {
		$this->set('data', $this->paginate());
	}
	
/**
 * Admin::view
 *
 * @param int $id SimplePage Id
 */
	function admin_view($id = null) {
		if (!$data = $this->SimplePage->read(null, $id)) {
			$this->Session->setFlash(
				__d('simple_pages', 'Invalid Simple Page.', true), 
				$this->flashLayouts['error']
			);
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set(compact('data'));
	}
	
/**
 * Admin::add
 */
	function admin_add() {
		if (!empty($this->data)) {
			$this->SimplePage->create();
			
			if ($this->SimplePage->save($this->data)) {
				$this->Session->setFlash(
					__d('simple_pages', 'The Simple Page has been saved.', true), 
					$this->flashLayouts['success']
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__d('simple_pages', 'The Simple Page could not be saved. Please, try again.', true), 
					$this->flashLayouts['notice']
				);
			}
		}
	}
	
/**
 * Admin::edit
 * 
 * @param int $id SimplePage Id
 */
	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(
				__d('simple_pages', 'Invalid Simple Page.', true),
				$this->flashLayouts['error']
			);
			$this->redirect(array('action' => 'index'));
		}
		
		if (!empty($this->data)) {
			if ($this->SimplePage->save($this->data)) {
				$this->Session->setFlash(
					__d('simple_pages', 'The Simple Page has been saved.', true),
					$this->flashLayouts['success']
				);
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(
					__d('simple_pages', 'The Simple Page could not be saved. Please, try again.', true),
					$this->flashLayouts['notice']
				);
			}
		}
			
		if (empty($this->data)) {
			$this->data = $this->SimplePage->read(null, $id);
		}
	}
	
/**
 * Admin::delete
 *
 * @param int $id SimplePage Id
 */
	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(
				__d('simple_pages', 'Invalid id for Simple Page.', true),
				$this->flashLayouts['error']
			);
			$this->redirect(array('action' => 'index'));
		}
		
		if ($this->SimplePage->delete($id)) {
			$this->Session->setFlash(
				__d('simple_pages', 'The Simple Page has been deleted.', true),
				$this->flashLayouts['success']
			);
			$this->redirect(array('action' => 'index'));
		}
		
		$this->Session->setFlash(
			__d('simple_pages', 'The Simple Page was not deleted.', true),
			$this->flashLayouts['notice']
		);
		$this->redirect(array('action' => 'index'));
	}
}