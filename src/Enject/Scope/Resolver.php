<?php
/*
 * Enject Library
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 */
/**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Resolver.php';
require_once 'Enject/Scope/Listener.php';

/**
 * Provides base functionality for {@link Enject_Value}s that provide scopes
 */
class Enject_Scope_Resolver
	implements Enject_Resolver,
		Enject_Scope_Listener
{
	/**
	 * @var Enject_Container_Base
	 */
	protected $_container;
	
	/**
	 * The scope this will use to determine whether to recreate an object or
	 * reuse it. When {@link Enject_Container::getScope()} resolves the scope
	 * name to a {@link Enject_Scope}, then the built object will be reused.
	 * @var String
	 */
	protected $_scope = 'default';
	
	/**
	 * Enject_Values may be used over several scopes in their lifetime.
	 * This variable keeps track of the different instances used in each scope
	 * @var String
	 */
	protected $_scopeValues = array();

	/**
	 * @param Enject_Scope $scope
	 * @param String $oldScopeId
	 * @see Enject_Scope_Value::cloneScope()
	 */
	function cloneScope($scope, $oldScopeId)
	{
		if(isset($this->_scopeValues[$oldScopeId]))
		{
			$instance = clone $this->_scopeValues[$oldScopeId];
			$this->_scopeValues[$scope->getScopeId()] = $instance;
		}
		return $this;
	}

	/**
	 * @return Enject_Container
	 * @throws Enject_Value_ContainerUndefinedException
	 */
	function getContainer()
	{
		return $this->_container;
	}

	/**
	 * @return String
	 */
	function getScope()
	{
		$return = $this->_scope;
		if(is_string($return))
		{
			try
			{
				$container = $this->getContainer();
				// TODO: Create and use Enject_Container interface
				if(is_callable(array($container, 'getScope')))
				{
					$return = $container->getScope($return);
				}
			}
			catch(Enject_Container_ScopeUnavailableException $exception)
			{
				// when the container scope isn't available:
				// the string value is used, meaning that no
				// no objects will be shared
			}
		}
		return $return;
	}

	/**
	 * Registers a value for the current scope
	 * @return Enject_Value_Scope
	 */
	function hasValue()
	{
		$return = false;
		$scope = $this->getScope();
		if($scope instanceOf Enject_Scope)
		{
			$return = isset($this->_scopeValues[$scope->getScopeId()]);
		}
		return $return;
	}

	/**
	 * @param Enject_Scope $scope
	 * @see Enject_Scope_Value::cloneScope()
	 * @return Enject_Value_Scope
	 */
	function removeScope($scope)
	{
		unset($this->_scopeValues[$scope->getScopeId()]);
		return $this;
	}

	/**
	 * If a value has been registered for the current scope, returns it.
	 * Otherwise returns null
	 * @return Mixed[]
	 */
	function resolve()
	{
		$return = null;
		if($this->hasValue())
		{
			$scope = $this->getScope();
			$return = $this->_scopeValues[$scope->getScopeId()];
		}
		return $return;
	}

	/**
	 * @param Enject_Container $component
	 * @return Enject_Container_Value_Base
	 */
	function setContainer($container)
	{
		$this->_container = $container;
		return $this;
	}

	/**
	 * @param Boolean $shared
	 * Sets the name of the scope to use.
	 *
	 * A scope determines when/where an object is recreated. The default scope
	 * reuses all objects. The prototype scope reuses no objects
	 * @param String $scope
	 * @return Enject_Value_Scope
	 */
	function setScope($scope)
	{
		$this->_scope = $scope;
		return $this;
	}

	/**
	 * Registers a value for the current scope
	 * @return Enject_Value_Scope
	 */
	function setValue($value)
	{
		$scope = $this->getScope();
		if($scope instanceOf Enject_Scope)
		{
			$this->_scopeValues[$scope->getScopeId()] = $value;
			$scope->registerListener($this);
		}
		return $this;
	}
}
