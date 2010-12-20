<?php
/*
 * Enject Library
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Injection/Collection.php';
require_once 'Enject/Value.php';

/**
 * This {@link Enject_Value} is responsible for creating AND injecting and
 * object when it is resolved.
 */
class Enject_Value_Builder
	implements Enject_Injection_Collection,
		Enject_Value
{
	/**
	 * @var String
	 */
	protected $_className;

	/**
	 * @var Enject_Container
	 */
	protected $_container;

	/**
	 * @var Enject_Injection_Collection
	 */
	protected $_injectionCollection;

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
	 * Parameters used for the constructor of the injector.
	 * @see registerParameter()
	 * @var Mixed[]
	 */
	protected $_parameters = array();

	/**
	 * Adds an injection (a method call with parameters)
	 * @param String|Enject_Injection $method
	 * @param Mixed $parameters
	 * @see addProperty() properties override regular injections
	 * @return Enject_Value_Builder
	 */
	function addInjection($method, $parameters = array())
	{
		$this->getInjectionCollection()->addInjection($method, $parameters);
		return $this;
	}

	/**
	 * @return String
	 */
	function getClassname()
	{
		return $this->_className;
	}

	/**
	 * @return String
	 */
	function getContainer()
	{
		return $this->_container;
	}

	/**
	 * @return Enject_Injection_Collection
	 */
	function getInjectionCollection()
	{
		if(!$this->_injectionCollection
			instanceOf Enject_Injection_Collection_Default)
		{
			require_once 'Enject/Injection/Collection/Default.php';
			$collection = new Enject_Injection_Collection_Default();
			$this->_injectionCollection = $collection;
		}
		return $this->_injectionCollection;
	}

	/**
	 * Gets the methods to call after the object is created
	 * @see addInjection()
	 * @see setProperty()
	 * @uses $_properties
	 * @uses $_injections
	 * @return Enject_Injection[]
	 */
	function getInjections()
	{
		return $this->getInjectionCollection()->getInjections();
	}

	/**
	 * Gets the injector parameters
	 * @see registerParameter()
	 * @uses $_parameters
	 * @return Mixed[]
	 */
	function getParameters()
	{
		return $this->_parameters;
	}

	/**
	 * Sets whether the instance will be shared or not
	 * @see setShared()
	 * @return Boolean
	 */
	function getShared()
	{
		return $this->_shared;
	}

	/**
	 * @return String[]
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		$class = new ReflectionClass($this->getClassname());
		if($class->implementsInterface('Enject_Value'))
		{
			// TODO: see if there is a better way to resolve this
			$return = $this->resolve()->getTypes();
		}
		else
		{
			$return = Enject_Tools::getTypes($class);
		}
		return $return;
	}

	/**
	 * @param Enject_Container $className
	 * @return Enject_Value_Builder
	 */
	function setContainer($container)
	{
		$this->_container = $container;
		return $this;
	}

	/**
	 * @param String $className
	 * @return Enject_Value_Builder
	 */
	function setClassname($className)
	{
		$this->_className = $className;
		return $this;
	}

	/**
	 * @param Boolean $shared
	 * @return Enject_Value_Builder
	 */
	function setShared($shared = true)
	{
		$this->_shared = $shared;
		return $this;
	}

	/**
	 * Sets the parameter to use in a constructor
	 * @param Mixed[] $parameters
	 * @return Enject_Value_Builder
	 */
	function setParameters($parameters)
	{
		$this->_parameters = $parameters;
		return $this;
	}

	/**
	 * Sets a property (a value set through setter methods)
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Value_Builder
	 */
	function registerParameter($name, $value)
	{
		$this->_parameters[$name] = $value;
		return $this;
	}

	/**
	 * Sets a property (a value set through setter methods)
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Value_Builder
	 */
	function registerProperty($property, $value)
	{
		$this->getInjectionCollection()->registerProperty($property, $value);
		return $this;
	}

	/**
	 * @return Mixed
	 * @uses getInjections()
	 * @uses Enject_Tools::inject()
	 */
	function resolve()
	{
		if($this->_shared && $this->_instance)
		{
			$return = $this->_instance;
		}
		else
		{
			require_once 'Enject/Tools.php';
			$container = $this->getContainer();
			$className = $this->getClassname();
			$return = new $className;
			$injections = $this->getInjections($container);
			$return = Enject_Tools::inject($container, $return, $injections);
			$container->inject($return);
			if($this->_shared)
			{
				$this->_instance = $return;
			}
		}
		return $return;
	}
}
