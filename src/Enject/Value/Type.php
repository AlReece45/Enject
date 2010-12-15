<?php

require_once 'Enject/Value.php';

class Enject_Value_Type
	implements Enject_Value
{
	/**
	 * @var String
	 */
	protected $_type;

	/**
	 * @return String
	 */
	function getType()
	{
		return $this->_type;
	}

	/**
	 * @return String[]
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		return Enject_Tools::getTypes(new ReflectionClass($this->getClassname()));
	}

	/**
	 * @param Enject_Container $container
	 * @return Mixed
	 */
	function resolve(Enject_Container $container)
	{
		return $container->resolveType($this->getType());
	}

	/**
	 * @param String $type
	 * @return Enject_Value_Type
	 */
	function setType($type)
	{
		$this->_type = $type;
		return $this;
	}
}