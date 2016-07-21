<?php

class Currency extends Model {

	public function __construct(DB\SQL $db) {
		parent::__construct($db,'currency');
	}
	
}
