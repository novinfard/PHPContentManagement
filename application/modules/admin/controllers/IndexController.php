<?php

class Admin_IndexController extends Zend_Controller_Action
{
	// controller initilize method
    public function init()
    {
        // call installer if new install
		$boot = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$config = $boot->getOptions();
		if($config['resources']['db']['params']['host'] == 'MFDBHOSTNAME')
		{
			$this->_redirect('installer');
		}

		// centralized authorization on module
		$action = $this->getRequest()->getActionName();
		$auth = Zend_Auth::getInstance();
		if(!$auth->hasIdentity() and ($action != 'login'))
		{
			$this->_redirect('admin/index/login');
		}
		
		
    }

	// admin homepage
    public function indexAction()
    {	
        $contents = new Application_Model_DbTable_Contents();
		$this->view->contents = $contents->fetchAll();
    }
	
	// admin authorization
    public function loginAction()
    {
		// login before?
		if(Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('admin');
		}

		// get the login form
		$request = $this->getRequest();
		$form = new Application_Form_Login();
		
		// login form submitted
		if($request->isPost())
		{
			if($form->isValid($this->_request->getPost()))
			{
				// get authorization pattern
				$authAdapter = $this->getAuthAdapter();

				$username = $form->getValue('username');
				$password = $form->getValue('password');

				$authAdapter->setIdentity($username)
							->setCredential($password);

				$auth = Zend_Auth::getInstance();

				try
				{
					$result = $auth->authenticate($authAdapter);
				}
				catch (Exception $e) 
				{
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}

				if ($result->isValid()) 
				{
					// get the login admin data
					$identity = $authAdapter->getResultRowObject();
					
					// save the log
					$data = array();
					$data['admin_id'] = $identity->id;
					$data['logdate'] = new Zend_Db_Expr('NOW()');
					$data['ip'] = $this->getClientIp();
					$model = new Application_Model_DbTable_Admin_Logs();
					$model->addLog($data);
					
					$authstorage = $auth->getStorage();
					$authstorage->write($identity);

					$this->_redirect('admin');
				}
				else
				{
					// set error message
					$this->view->errorMessage = "Username or password is wrong!";
				}
			}
		}

		$this->view->loginForm = $form;
 
    }
	
	private function getAuthAdapter()
	{
		$authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
		$authAdapter->setTableName('admins')
					->setIdentityColumn('username')
					->setCredentialColumn('password')
					->setCredentialTreatment('MD5(?)');

		return $authAdapter;
	}
	
	public function logoutAction()
	{
		Zend_Auth::getInstance()->clearIdentity();
		$this->_redirect('admin/index/login');
	}

	// add content
    public function addAction()
    {
		// get the content form
        $form = new Application_Form_Content();
		$form->submit->setLabel('Submit');
		$this->view->form = $form;
		
		// check if new content submitted
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			// check if the form is valid
			if ($form->isValid($formData)) {
			
				// set the storing date
				$data = array();
				$data['title'] = $form->getValue('title');
				$data['fulltext'] = $form->getValue('fulltext');
				$data['introtext'] = $form->getValue('introtext');
				$data['published'] = $form->getValue('published');
				$data['pubdate'] = new Zend_Db_Expr('NOW()');
				
				// store new content
				$model = new Application_Model_DbTable_Contents();
				$model->addContent($data);
				
				// redirect to index
				$this->_helper->redirector('index');
			} 
			// populate the invalid form and show the errors
			else 
			{
				$form->populate($formData);
			}
		}
    }

	// edit content
    public function editAction()
    {
		// get the content form
        $form = new Application_Form_Content();
		$form->submit->setLabel('Submit');
		$this->view->form = $form;
		
		// check if the edited form submitted
		if ($this->getRequest()->isPost()) {
			$formData = $this->getRequest()->getPost();
			
			// check if the form is valid
			if ($form->isValid($formData)) {
				$data = array();
				$data['id'] = (int)$form->getValue('id');
				$data['title'] = $form->getValue('title');
				$data['fulltext'] = $form->getValue('fulltext');
				$data['introtext'] = $form->getValue('introtext');
				$data['published'] = $form->getValue('published');
				$model = new Application_Model_DbTable_Contents();
				$model->updateContent($data, $id);
				$this->_helper->redirector('index');
			}
			// populate the invalid form and show the errors
			else 
			{
				$form->populate($formData);
			}
		} 
		// edit form with the related data
		else 
		{
			$id = $this->_getParam('id', 0);
			if ($id > 0) {
				$model = new Application_Model_DbTable_Contents();
				$form->populate($model->getContent($id));
			}
		}
    }

	// delete content
    public function deleteAction()
    {
		// if delete submitted
		if ($this->getRequest()->isPost()) {
			$del = $this->getRequest()->getPost('del');
			if ($del == 'Yes') { 
				$id = $this->getRequest()->getPost('id');
				
				// delete the content
				$contents = new Application_Model_DbTable_Contents();
				$contents->deleteContent($id);
			}
			$this->_helper->redirector('index');
		}
		// the delete confirmation page 
		else 
		{
			$id = $this->_getParam('id', 0);
			$contents = new Application_Model_DbTable_Contents();
			$this->view->content = $contents->getContent($id);
		}
    }
	
	// get the client IP address
	private function getClientIp() {
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   $ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';
		return $ipaddress;
	}

}







