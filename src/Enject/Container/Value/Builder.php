<?php
/*
 * Enject Library
 * Copyright 2010-2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010-2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Container/Value/Base.php';
require_once 'Enject/Injection/Collection.php';
require_once 'Enject/Mode/Value.php';
require_once 'Enject/Scope/Value.php';

/**
 * This {@link Enject_Value} is responsible for creating AND injecting and
 * object when it is resolved.
 */
class Enject_Container_Value_Builder
	extends Enject_Container_Value_Base
	implements Enject_Mode_Value,
		Enject_Scope_Value
{
	/**
	 * @var String
	 */
	protected $_className;

	/**
	 * @var Enject_Injection_Collection
	 */
	protected $_injectionCollection;

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
	 * @return String
	 */
	function getMode()
	{
		return $this->_getModeResolver()->getMode();
	}

	/**
	 * Builds the objects without doing any checks on the mode.
	 * @return Mixed
	 */
	function getValue()
	{
		// check to see if an object has been built for this scope already
		$className = $this->getClassname();
		$scopeResolver = $this->_getScopeResolver();
		$return = $scopeResolver->resolve();

		// if the object hasn't been built, build it and tell the scope resolver
		if(!$return instanceOf $className)
		{
			require_once 'Enject/Tools.php';
			$return = new $className;

			// inject the object
			$container = $this->getContainer();
			$injections = $this->getInjections($container);
			Enject_Tools::inject($container, $return, $injections);
			$container->inject($return);
			$scopeResolver->setValue($return);
		}
		return $return;
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
	 * @return String
	 */
	function getScope()
	{
		return $this->_getScopeResolver()->getScope();
	}

	/**
	 * Gets the types (parent classes and interfaces) associated with the
	 * requested type.
	 * @return String[]
	 * @uses Enject_Tools::getModeTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		return Enject_Tools::getModeTypes($this, $this->getClassname());
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
	 * @param String $mode
	 * @return Enject_Container_Type
	 */
	function setMode($mode)
	{
		$this->_getModeResolver()->setMode($mode);
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
	 * @param String $mode
	 * @return Enject_Container_Type
	 */
	function setScope($scope)
	{
		$this->_getScopeResolver()->setScope($scope);
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
	 * Performs the actual resolation
	 * @param Enject_Container $container
	 * @return Mixed
	 * @uses Enject_Container::resolveType()
	 */
	function resolve()
	{
		// use the mode to resolve if needed
		$modeResolver = $this->_getModeResolver();
		$modeResolver->setValue($this->getValue());
		return $modeResolver->resolve();
	}
}
