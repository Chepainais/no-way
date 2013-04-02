<?php
class Application_Model_Articles {
	protected $_name;
	protected $_text;
	protected $_order_id;
	protected $_status;
	
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}
	
	/**
	 * @return the $_name
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * @return the $_text
	 */
	public function getText() {
		return $this->_text;
	}

	/**
	 * @return the $_order_id
	 */
	public function getOrder_id() {
		return $this->_order_id;
	}

	/**
	 * @return the $_status
	 */
	public function getStatus() {
		return $this->_status;
	}

	/**
	 * @param field_type $_name
	 */
	public function setName($_name) {
		$this->_name = $_name;
	}

	/**
	 * @param field_type $_text
	 */
	public function setText($_text) {
		$this->_text = $_text;
	}

	/**
	 * @param field_type $_order_id
	 */
	public function setOrder_id($_order_id) {
		$this->_order_id = $_order_id;
	}

	/**
	 * @param field_type $_status
	 */
	public function setStatus($_status) {
		$this->_status = $_status;
	}
	
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}
	public function save(Application_Model_Articles $article)
	{
		$data = array(
				'name'   => $article->getName(),
				'text' => $article->getText(),
				'time_created' => date('Y-m-d H:i:s'),
		);
	
		if (null === ($id = $guestbook->getId())) {
			unset($data['id']);
			$this->getDbTable()->insert($data);
		} else {
			$this->getDbTable()->update($data, array('id = ?' => $id));
		}
	}

}

?>