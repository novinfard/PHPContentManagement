<?php
// Contents Table
class Application_Model_DbTable_Admin_Logs extends Zend_Db_Table_Abstract
{

    protected $_name = 'logs';

	// Add a log info
	public function addLog($data)
	{
		$this->insert($data);
	}
	
}

