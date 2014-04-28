<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
       // call installer if new install
		$boot = Zend_Controller_Front::getInstance()->getParam('bootstrap');
		$config = $boot->getOptions();
		if($config['resources']['db']['params']['host'] == 'MFDBHOSTNAME')
		{
			$this->_redirect('installer');
		}
	}

	// Home Page
    public function indexAction()
    {
        $contents = new Application_Model_DbTable_Contents();
		$this->view->contents = $contents->fetchAll('published = 1', 'pubdate DESC', 5);
    }
	
	// Each Content Display
	public function displayAction()
    {
		$id = $this->_getParam('id', 0);
        $model = new Application_Model_DbTable_Contents();
		$this->view->item  = $model->getContent($id);
		
        $cmmodel = new Application_Model_DbTable_Comments();
		$this->view->comments  = $cmmodel->fetchAll(
			array('published = 1','content_id = '.$id), 'pubdate DESC');
		
        $form = new Application_Form_Comment();
		$form->submit->setLabel('Add Comment');
		$this->view->form = $form;
		
		if ($this->getRequest()->isPost()) {
			$this->addAction($form);
		}
    }

	// Add Comment
    public function addAction($form)
    {
			$formData = $this->getRequest()->getPost();
			
			// If the form data is valid
			if ($form->isValid($formData)) {
				$data = array();
				$data['content_id'] = $form->getValue('content_id');
				$data['commenter'] = $form->getValue('commenter');
				$data['fulltext'] = $form->getValue('fulltext');
				$data['pubdate'] = new Zend_Db_Expr('NOW()');
				
				// insert comment to database
				$cmmodel = new Application_Model_DbTable_Comments();
				$cmmodel->addComment($data);
				
				// redirect to homepage
				$this->_helper->redirector('display', null, null, array('id'=> $form->getValue('content_id')) );
			} else {
				// show the invalid form with errors
				$form->populate($formData);
			}
    }

}







