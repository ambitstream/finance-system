<?php

class Flow extends Model {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'flow');
		
		// Загружаем категории
		// $categories_in = new CategoryIn($db);
		// $currencies = new Currency($db);
    }
	
    public function all() {
        $this->load(
			NULL,
			array( 'order' => 'date DESC, id DESC')
		);
		return $this->query;
    }
	
    public function allpaged($page = 0, $limit = 40, $include = false, $search = '') {
		$page = $page != '' ? $page : 0;
		$limit = $limit != '' ? $limit : 40;
		$search = $search != '' ? $search : '';
		$where = $include != false ? array(
			"category_id in ('".implode("','",$include)."') AND comment LIKE ?", "%".$search."%" 
		) : NULL;
		
		return $this->paginate($page,$limit, $where, array(
			'order' => 'date DESC, id DESC',
		));		
    }	
	
	public function showcatsum() {
        return $this->db->exec('SELECT * FROM flow');
	}
	
    public function add() {
        $this->copyFrom('POST');
        $this->save();
		// $wallet = new Wallet();
		// $wallet->recoutCurrentCache();
    }
	
}
