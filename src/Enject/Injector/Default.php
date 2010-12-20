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
require_once 'Enject/Injector.php';

/**
 * Default implementation of a {@link Enject_Injector}
 */
class Enject_Injector_Default
	implements Enject_Injection_Collection,
		Enject_Injector
{

	/**
	 * @var Enject_Injection_Collection_Default
	 */
	protected $_injectionCollection;

	/**
	 * Adds an injection (a method call with parameters)
	 * @param String|Enject_Injection $method
	 * @param Mixed $parameters
	 * @see addProperty() properties override regular injections
	 * @return Enject_Injection_Collection_Default
	 */
	function addInjection($method, $parameters = array())
	{
		$collection = $this->getInjectionCollection();
		$collection->addInjection($method, $parameters);
		return $this;
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
	 * @return Enject_Injection_Collection
	 */
	function getInjectionCollection()
	{
		if(!$this->_injectionCollection)
		{
			require_once 'Enject/Injection/Collection/Default.php';
			$collection = new Enject_Injection_Collection_Default();
			$this->_injectionCollection = $collection;
		}
		return $this->_injectionCollection;
	}

	/**
	 * Injects values into an object (probably using method calls)
	 * @param Mixed $object
	 * @return Enject_Injector_Default
	 * @uses getInjections()
	 * @uses Enject_Tools::inject()
	 */
	function inject($container, $object)
	{
		require_once 'Enject/Tools.php';
		$injections = $this->getInjections();
		$object = Enject_Tools::inject($container, $object, $injections);
		return $this;
	}

	/**
	 * Sets a property (a value set through setter methods)
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Injection_Collection_Default
	 */
	function registerProperty($name, $value)
	{
		$this->getInjectionCollection()->registerProperty($name, $value);
		return $this;
	}
}
