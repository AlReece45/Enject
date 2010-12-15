<?php

require_once 'Enject/Value.php';

class Enject_Value_Service
	implements Enject_Value
{
	/**
	 * @var String
	 */
	protected $_service;

	/**
	 * @return String
	 */
	function getService()
	{
		return $this->_service;
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
	 * @param String $service
	 * @return Enject_Value_Service
	 */
	function setService($service)
	{
		$this->_service = $service;
		return $this;
	}

	/**
	 * @param Enject_Container $container
	 * @return Mixed
	 */
	function resolve(Enject_Container $container)
	{
		return $container->resolveService($this->getService());
	}
}