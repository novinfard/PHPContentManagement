<?php

class Application_Form_Setup extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
 
        $this->addElement('text', 'dbhostname', array(
			'label' => 'Host Name',
			'required' => true,
			));
			
 
        $this->addElement('text', 'dbusername', array(
			'label' => 'Username',
            'required' => true,
            ));
			
        $this->addElement('text', 'dbpassword', array(
			'label' => 'Password',
			));
			
        $this->addElement('text', 'dbname', array(
			'label' => 'Name',
            'required' => true,
			));
 
        $this->addElement('submit', 'submit', array(
            'label'    => 'Setup',
			'class'	   => 'btn btn-primary btn-lg'
            ));
 
    }
}