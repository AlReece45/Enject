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

/**
 * Base object for commonly used functionality in the builders
 */
abstract class Enject_Value_Base
{
	/**
	 * Default mode to use when resolving objects. Currently only affects one
	 * thing: objects that implement Enject_Value. In the default mode, it
	 * resolves them. To return a Builder (without resolving it), use the
	 * "builder" mode
	 */
	const MODE_DEFAULT = 'default';

	/**
	 * Use this mode when you want to consturct a {@link Enject_Value} without
	 * having it resolved.
	 */
	const MODE_VALUE = 'value';

	/**
	 * @var Enject_Container
	 */
	protected $_container;

	/**
	 * Mode to use for resolving objects
	 * @var String
	 */
	protected $_mode = self::MODE_DEFAULT;

	/**
	 * @return Enject_Container
	 */
	function getContainer()
	{
		if(!$this->_container instanceOf Enject_Container)
		{
			require_once 'Enject/Exception.php';
			throw new Enject_Exception('Container not set');
		}
		return $this->_container;
	}

	/**
	 * Gets the currently set build mode
	 * @see setMode()
	 * @return String
	 */
	function getMode()
	{
		return $this->_mode;
	}

	/**
	 * @param Enject_Container $component
	 * @return Enject_Value_Base
	 */
	function setContainer($container)
	{
		$this->_container = $container;
		return $this;
	}

	/**
	 * Sets the resolving mode:
	 * @see MODE_DEFAULT
	 * @see MODE_VALUE
	 * @return Enject_Value_Base
	 */
	function setMode($mode)
	{
		$this->_mode = $mode;
		return $this;
	}

	/**
	 * @param Mixed $object
	 * @return Mixed
	 */
	protected function _resolve($object)
	{
		if($object instanceOf Enject_Value
			 && $this->getMode() != self::MODE_VALUE)
		{
			$object = $object->resolve();
		}
		return $object;
	}
}
