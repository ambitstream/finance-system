<?php

class Model extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db, $table) {
        parent::__construct($db, $table);
    }
	
	public function getName($id)
	{
		$cat = $this->retById($id);
		return $cat->name;
	}

    public function all() {
        $this->load(
			NULL,
			array( 'order' => 'id ASC')
		);
        return $this->query;
    }

    public function getById($id) {
        $this->load(array('id=?',$id));
        $this->copyTo('POST');
    }
	
    public function retById($id) {
        return $this->load(array('id=?',$id));
    }

    public function add() {
        $this->copyFrom('POST');
        $this->save();
    }

    public function edit($id) {
        $this->load(array('id=?',$id));
        $this->copyFrom('POST');
        $this->update();
    }

    public function delete($id) {
        $this->load(array('id=?',$id));
        $this->erase();
    }
}
