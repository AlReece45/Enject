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
require_once 'Enject/Value.php';
require_once 'Enject/Value/Base.php';

/**
 * This {@link Enject_Value} is responsible for resolving a value to a type.
 * @see Enject_Container_Base::getInstance()
 */
class Enject_Value_Type
	extends Enject_Value_Base
	implements Enject_Value
{

	/**
	 * The name of the type that will be resolved
	 * @var String
	 */
	protected $_type;

	/**
	 * Gets the name of the type that will be resolved.
	 * @return String
	 * @uses $_type
	 */
	function getType()
	{
		return $this->_type;
	}

	/**
	 * Gets the types (parent classes and interfaces) associated with the
	 * requested ty-e.
	 * @return String[]
	 * @uses Enject_Tools::getTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		$class = new ReflectionClass($this->getType());
		return Enject_Tools::getTypes($class);
	}

	/**
	 * Performs the actual resolation
	 * @param Enject_Container_Base $container
	 * @return Mixed
	 * @uses Enject_Container_Base::resolveType()
	 */
	function resolve()
	{
		$container = $this->getContainer();
		return $this->_resolve($container->resolveType($this->getType()));
	}

	/**
	 * Sets the type that will be used when resolved.
	 * @param String $type
	 * @return Enject_Value_Type
	 */
	function setType($type)
	{
		$this->_type = $type;
		return $this;
	}
}