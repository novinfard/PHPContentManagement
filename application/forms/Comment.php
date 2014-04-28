<?php
// Comment Form
class Application_Form_Comment extends Zend_Form
{
	// initialize method
	// all the form elements implemented here
    public function init()
    {
		// name of the form
        $this->setName('comment');
		
		// hidden field by integer filter
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		// set content id as a hidden field
		$cid = Zend_Controller_Front::getInstance()->getRequest()->getParam('id', 0);
		$content_id = new Zend_Form_Element_Hidden('content_id');
		$content_id->setValue($cid );
				
		// commenter text type field with validation
		// addValidator is just a sample, setRequired method is enough
		$commenter  = new Zend_Form_Element_Text('commenter');
		$commenter->setLabel('Your Name')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
		
		// fulltext textarea field with validation
		$fulltext  = new Zend_Form_Element_Textarea('fulltext');
		$fulltext->setLabel('Your Message')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->setAttrib('COLS', '40')
				->setAttrib('ROWS', '4')
				->addValidator('NotEmpty');
		
		// submit type field
		// 'btn' class added to form element
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
				->class = 'btn';
		
		// All the above elements added to the form		
		$this->addElements(array($id, $content_id, $commenter, $fulltext, $submit));
    }


}

