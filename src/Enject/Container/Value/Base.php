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
require_once 'Enject/Container/Value.php';

/**
 * This {@link Enject_Value} is a base class for all
 * {@link Enject_Container_Value}s
 */
abstract class Enject_Container_Value_Base
	implements Enject_Container_Value
{
	/**
	 * @var Enject_Container_Base
	 */
	protected $_container;

	/**
	 * @var Enject_Mode_Resolver
	 */
	protected $_resolverMode;

	/**
	 * @var Enject_Scope_Resolver
	 */
	protected $_resolverScope;

	/**
	 * @return Enject_Container_Base
	 * @throws Enject_Container_Value_ContainerUndefinedException
	 */
	function getContainer()
	{
		if(!$this->_container instanceOf Enject_Container_Base)
		{
			require_once 'Enject/Container/Value/ContainerUndefinedException.php';
			throw new Enject_Container_Value_ContainerUndefinedException();
		}
		return $this->_container;
	}

	/**
	 * @return Enject_Mode_Resolver
	 */
	protected function _getModeResolver()
	{
		if(!$this->_resolverMode instanceOf Enject_Mode_Resolver)
		{
			require_once 'Enject/Mode/Resolver.php';
			$this->_resolverMode = new Enject_Mode_Resolver();
			$this->_resolverMode->setMode(Enject_Mode_Resolver::MODE_RESOLVE);
		}
		return $this->_resolverMode;
	}

	/**
	 * @return Enject_Scope_Resolver
	 */
	protected function _getScopeResolver()
	{
		if(!$this->_resolverScope instanceOf Enject_Scope_Resolver)
		{
			require_once 'Enject/Scope/Resolver.php';
			$this->_resolverScope = new Enject_Scope_Resolver();
			try
			{
				$this->_resolverScope->setContainer($this->getContainer());
			}
			catch(Enject_Container_Value_ContainerUndefinedException $e)
			{
				// it will likely be set later
			}
		}
		return $this->_resolverScope;
	}

	/**
	 * @param Enject_Container_Base $component
	 * @return Enject_Container_Value_Base
	 */
	function setContainer($container)
	{
		$this->_container = $container;
		if($this->_resolverScope instanceOf Enject_Scope_Resolver)
		{
			$this->_resolverScope->setContainer($container);
		}
		return $this;
	}
}
