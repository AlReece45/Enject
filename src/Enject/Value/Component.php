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
 * This {@link Enject_Value} is responsible for refering to a Component
 * @see Enject_Container::registerComponent()
 */
class Enject_Value_Component
	extends Enject_Value_Base
	implements Enject_Value
{
	/**
	 * The name of the component
	 * @var String
	 */
	protected $_name;

	/**
	 * Gets the name of the component that will be used when resolving
	 * @return String
	 * @uses $_name
	 */
	function getName()
	{
		return $this->_name;
	}

	/**
	 * Gets the types (className, parent classes, and interfaces) of the object
	 * that will be returned.
	 * @return String[]
	 * @uses Enject_Tools::getTypes()
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		$object = $this->resolve();
		return Enject_Tools::getTypes($object);
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
	function resolve()
	{
		$container = $this->getContainer();
		return $this->_resolve($container->resolveComponent($this->getName()));
	}
}
