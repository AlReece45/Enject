<?php

require_once 'Enject/Blueprint/Proxy.php';
require_once 'Enject/Value.php';

class Enject_Value_Builder
	implements Enject_Value, Enject_Blueprint_Proxy
{
	/**
	 * @var Enject_Blueprint
	 */
	protected $_blueprint;

	/**
	 * @var String
	 */
	protected $_className;
	
	/**
	 * If there is an instance of the object, and the object is shared. This
	 * is set to the shared instance of the object.
	 * @var Mixed
	 */
	protected $_instance;

	/**
	 * Whether or not the object is shared (an instance will be reused)
	 * @var <type>
	 */
	protected $_shared = false;

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
	 * @return String
	 */
	function getClassname()
	{
		return $this->_className;
	}

	/**
	 * @return Mixed[]
	 * @uses getBlueprint()
	 * @uses Enject_Blueprint::getInjections()
	 */
	function getInjections(Enject_Container $container)
	{
		return $this->getBlueprint()->getInjections($container);
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
	 * @return String[]
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		return Enject_Tools::getTypes(new ReflectionClass($this->getClassname()));
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
	 * @param String $className
	 * @return Enject_Builder_Default
	 */
	function setClassname($className)
	{
		$this->_className = $className;
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
	 * @param <type> $shared
	 * @return Enject_Value_Builder
	 */
	function setShared($shared = true)
	{
		$this->_shared = $shared;
		return $this;
	}

	/**
	 * @return Mixed
	 * @uses getInjections()
	 * @uses Enject_Tools::inject()
	 */
	function resolve(Enject_Container $container)
	{
		if($this->_shared && $this->_instance)
		{
			$return = $this->_instance;
		}
		else
		{
			require_once 'Enject/Tools.php';
			$return = $container->getInstance($this->getClassname());
			$return = Enject_Tools::inject($return, $this->getInjections($container));
			if($this->_shared)
			{
				$this->_instance = $return;
			}
		}
		return $object;
	}
}