<?php
class Application_Form_ApplicationArticles extends Zend_Form
{

	public function init()
	{
		$this->setMethod('post');// Add an name element
		$this->addElement('Text', 'name', array(
				'label'      => $this->translate($field),
				'required'   => true,


		));
		// Add an text element
		$this->addElement('Text', 'text', array(
				'label'      => $this->translate($field),
				'required'   => true,


		));
		// Add an order_id element
		$this->addElement('Text', 'order_id', array(
				'label'      => $this->translate($field),
				'required'   => false,


		));
		// Add an status element
		$this->addElement('Select', 'status', array(
				'label'      => $this->translate($field),
				'required'   => true,


		));
		// Add an Submit button
		$this->addElement('submit', 'submit', array(
				'ignore'   => true,
				'label'    => 'Submit',
		));
	}


}