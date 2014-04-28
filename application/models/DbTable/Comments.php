<?php
// Comments Table
class Application_Model_DbTable_Comments extends Zend_Db_Table_Abstract
{

    protected $_name = 'comments';
	
	// Adding Comments
	public function addComment($data)
	{
		$this->insert($data);
	}
	
}
