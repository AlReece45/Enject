<?php

require_once 'Enject/Value.php';

class Enject_Value_Component
	implements Enject_Value
{
	/**
	 * @var String
	 */
	protected $_name;

	/**
	 * @return String
	 */
	function getName()
	{
		return $this->_name;
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
	 * @param String $component
	 * @return Enject_Value_Component
	 */
	function setName($name)
	{
		$this->_name = $name;
		return $this;
	}

	/**
	 * @param Enject_Container $container
	 * @return Mixed
	 */
	function resolve(Enject_Container $container)
	{
		return $container->resolveComponent($this->getName());
	}
}