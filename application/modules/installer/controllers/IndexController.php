<?php

class Installer_IndexController extends Zend_Controller_Action
{

    public function init()
    {
	
    }

	// Home Page
    public function indexAction()
    {
       // installed before check
		$boot = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$config = $boot->getOptions();
		if($config['resources']['db']['params']['host'] != 'MFDBHOSTNAME')
		{
			$this->_redirect('');
		}
		
		$form = new Application_Form_Setup();
			
		// form submitted
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			// If the form data is valid
			if ($form->isValid($formData)) {
				$data =array();
				$data['host'] = $form->getValue('dbhostname');
				$data['username'] = $form->getValue('dbusername');
				$data['password'] = $form->getValue('dbpassword');
				$data['dbname'] = $form->getValue('dbname');
				
				try {
					$db = Zend_Db::factory('Pdo_Mysql', $data);
					@$db->getConnection();
					
					//save Db in registry for later use
					Zend_Registry::set("db", $db);
					
					$config_file = APPLICATION_PATH . '/configs/application.ini';
					$config = new Zend_Config_Ini($config_file, 
												null,
												array('skipExtends' => true, 'allowModifications' => true)
												);
							 
					// Modify a value
					$config->production->resources->db->params->host = $data['host'];
					$config->production->resources->db->params->username = $data['username'];
					$config->production->resources->db->params->password = $data['password'];
					$config->production->resources->db->params->dbname = $data['dbname'];
							 
					// Write the config file
					$writer = new Zend_Config_Writer_Ini(array('config'   => $config,
																'filename' => $config_file)
														);
					$writer->write();
					
					$sqlfile = file_get_contents( APPLICATION_PATH . '/modules/installer/install.sql' );

					$stmt = $db->query($sqlfile);
					$this->_redirect('installer/index/success');
					
					
				} catch (Zend_Db_Adapter_Exception $e) {
					$this->view->errorMessage = "Database informaton is wrong!";
					$form->populate($formData);
				}
		
			}
			else {
				// show the invalid form with errors
				$form->populate($formData);
			}
		}
		
		$this->view->form = $form;
	}
	
	// successfull setup
	public function successAction()
    {

    }

}







