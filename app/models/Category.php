<?php

class Category extends Model {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'category');
    }
	
    public function all($type = false) {
		
        $this->load(
			$type ? array("`type` = ?", $type) : NULL,
			array( 'order' => 'id ASC')
		);
        return $this->query;
    }

}
