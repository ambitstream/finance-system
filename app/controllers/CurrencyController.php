<?php

class CurrencyController extends Controller {

	public function index()
    {
        $currency = new Currency($this->db);
        $this->f3->set('currencies',$currency->all());
        $this->f3->set('page_head','Currency list');
        $this->f3->set('view','currency/list.htm');
	}

}