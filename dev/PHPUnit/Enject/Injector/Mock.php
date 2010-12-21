<?php
/*
 * Enject Library Tests
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */
require_once 'Enject/Injector.php';

/*
 * Helps test the and verify the injection functionality of
 * {@link Enject_Container}
 */
class Test_Enject_Injector_Mock
	implements Enject_Injector
{
	/**
	 * Constructor
	 */
	function __construct()
	{
		$this->_injectedObjects = new SplObjectStorage();
	}

	/**
	 * @param Enject_Container $container
	 * @param Object $object
	 */
	function inject($container, $object)
	{
		$this->_injectedObjects->attach($object);
	}

	/**
	 * @param Object $object
	 * @return Boolean
	 */
	function isObjectInjected($object)
	{
		return $this->_injectedObjects->contains($object);
	}
}
