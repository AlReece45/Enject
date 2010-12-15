<?php

require_once 'Enject/Blueprint/Proxy.php';
require_once 'Enject/Target.php';

class Enject_Target_Default
	implements Enject_Blueprint_Proxy
{
	/**
	 * @var Enject_Blueprint
	 */
	protected $_blueprint;

	/**
	 * Adds an injection (a method call with parameters)
	 * @param String $method
	 * @param Mixed $parameters
	 * @return Enject_Target_Default
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::addInjection()
	 */
	function addInjection($method, $parameters = array())
	{
		$this->getBlueprint()->addInjection($method, $parameters);
		return $this;
	}
	
	/**
	 * @return Enject_Blueprint
	 */
	function getBlueprint()
	{
		if(!isset($this->_blueprint))
		{
			require_once 'Enject/Blueprint/Default.php';
			$this->_blueprint = new Enject_Blueprint_Default();
		}
		return $this->_blueprint;
	}

	/**
	 * @return Enject_Container
	 */
	function getContainer()
	{
		return $this->_container;
	}

	/**
	 * @return Mixed[]
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::getInjections()
	 */
	function getInjections()
	{
		return $this->getBlueprint()->getInjections($this->getContainer());
	}

	/**
	 * @return Mixed[]
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::getParameters()
	 */
	function getParameters()
	{
		return $this->getBlueprint()->getParameters();
	}

	/**
	 * @return Mixed[]
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::getProperties()
	 */
	function getProperties()
	{
		return $this->getBlueprint()->getProperties();
	}

	/**
	 * Sets the blueprint used
	 * @param Enject_Blueprint $blueprint
	 * @return Enject_Target_Default
	 */
	function setBlueprint(Enject_Blueprint $blueprint)
	{
		$this->_blueprint = $blueprint;
		return $this;
	}

	/**
	 * Sets the blueprint used
	 * @param Enject_Blueprint $blueprint
	 * @return Enject_Target_Default
	 */
	function setContainer(Enject_Container $container)
	{
		$this->_container = $container;
		return $this;
	}

	/**
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Target_Default
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::getProperties()
	 */
	function setProperty($property, $value)
	{
		$this->getBlueprint()->setProperty($property, $value);
		return $this;
	}
	
	/**
	 * Injects values into an object (probably using method calls)
	 * @param Mixed $object
	 * @return Enject_Target_Default
	 * @uses getInjections()
	 * @uses Enject_Tools::inject()
	 */
	function inject($object)
	{
		require_once 'Enject/Tools.php';
		$object = Enject_Tools::inject($object, $this->getInjections());
		return $object;
	}
}