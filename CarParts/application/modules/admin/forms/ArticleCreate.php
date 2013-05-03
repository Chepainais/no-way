<?php
class Admin_Form_ArticleCreate extends Zend_Form {
	public function init() {
	    $language = new Zend_Form_Element_Select('language');
	    $language->setLabel('language')
	             ->setMultiOptions(array('lv' => 'Latvian', 'ru' => 'Russian', 'en' => 'English', 'no' => 'Norvegian', 'fi' => 'Finnish', 'sv' => 'Swedish', 'da' => 'Danish', 'nl' => 'Dutch'));
	    
		$name = new Zend_Form_Element_Text('name');
		$name->setLabel('Article name');
		
		$alias = new Zend_Form_Element_Text('alias');
		$alias->setLabel('Article alias');
		
		
		
		$text = new Zend_Form_Element_Textarea('text');
		$text->setLabel('Article text')
		    ->setAttrib('class', 'ckeditor')
// 			->setRequired(true)
		;
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Create article');
		
		$elements = array($language, $name, $alias, $text, $submit);
		$this->addElements($elements);
	}
}

?>