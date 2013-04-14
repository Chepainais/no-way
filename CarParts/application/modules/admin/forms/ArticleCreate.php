<?php
class Admin_Form_ArticleCreate extends Zend_Form {
	public function init() {
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Article name');
		
		$alias = new Zend_Form_Element_Text('alias');
		$alias->setLabel('Article alias');
		
		$text = new Zend_Form_Element_Textarea('text');
		$text->setLabel('Article text')
		    ->setAttrib('class', 'ckeditor')
// 			->setRequired(true)
		;
		
		$submit = new Zend_Form_Element_Submit('Sumbit');
		$submit->setLabel('Create article');
		
		$elements = array($name, $alias, $text, $submit);
		$this->addElements($elements);
	}
}

?>