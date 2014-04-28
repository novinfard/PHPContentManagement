<?php
// Contents Table
class Application_Model_DbTable_Contents extends Zend_Db_Table_Abstract
{

    protected $_name = 'contents';

	// Get a single content
	public function getContent($id) 
	{
		$id = (int)$id;
		$row = $this->fetchRow('id = ' . $id);
		if (!$row) {
			throw new Exception("Could not find row {$id}");
		}
		return $row->toArray(); 
	}
	
	// Add a content
	public function addContent($data)
	{
		$this->insert($data);
	}
	
	// Update a content
	public function updateContent($data)
	{
		$this->update($data, 'id = '. $data['id']);
	}
	
	// Delete a content
	public function deleteContent($id)
	{
		$this->delete('id =' . (int)$id);
	}
	
}

