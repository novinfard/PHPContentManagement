<?php
// Content Form
class Application_Form_Content extends Zend_Form
{
	// initialize method
	// all the form elements implemented here
    public function init()
    {
		// name of the form
        $this->setName('content');
		
		// hidden field by integer filter
		$id = new Zend_Form_Element_Hidden('id');
		$id->addFilter('Int');
		
		// title text type field
		// addValidator is just a sample, setRequired method is enough
		$title  = new Zend_Form_Element_Text('title');
		$title->setLabel('Title')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->addValidator('NotEmpty');
		
		// introtext textarea field with validation
		$introtext  = new Zend_Form_Element_Textarea('introtext');
		$introtext->setLabel('Intro Text')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->setAttrib('COLS', '40')
				->setAttrib('ROWS', '3')
				->addValidator('NotEmpty');
				
		// fulltext textarea field with validation
		// setAttrib set HTML elements attribute of the field
		$fulltext  = new Zend_Form_Element_Textarea('fulltext');
		$fulltext->setLabel('Full Text')
				->setRequired(true)
				->addFilter('StripTags')
				->addFilter('StringTrim')
				->setAttrib('COLS', '40')
				->setAttrib('ROWS', '6')
				->addValidator('NotEmpty');
		
		// published radio buttons field 
		// setValue method set the default selected option
		$published  = new Zend_Form_Element_Radio('published');
		$published->setLabel('Status')
				->addMultiOption(1, 'published')
				->addMultiOption(0, 'unpublished')
				->setSeparator(' &nbsp;&nbsp;&nbsp;&nbsp;')
				->setValue(1)
				->setRequired(true)
				->addValidator('NotEmpty');
		
		// submit type field
		// 'btn' class added to form element		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton')
				->class = 'btn';
		
		// All the above elements added to the form
		$this->addElements(array($id, $title, $introtext, $fulltext, $published, $submit));
    }


}

