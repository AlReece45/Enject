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
require_once 'Enject/Scope/Value.php';
require_once 'Enject/Value/Base.php';
require_once 'Enject/Value.php';

/**
 * This {@link Enject_Value} is responsible for creating AND injecting and
 * object when it is resolved.
 */
class Enject_Value_Builder
	extends Enject_Value_Base
	implements Enject_Injection_Collection,
		Enject_Scope_Value,
		Enject_Value
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
	 * If there is an instance of the object, and the object is shared. This
	 * is set to the shared instance of the object.
	 * @var Mixed[]
	 */
	protected $_instances = array();

	/**
	 * The scope this will use to determine whether to recreate an object or
	 * reuse it. When {@link Enject_Container_Base::getScope()} resolves the scope
	 * name to a {@link Enject_Scope}, then the built object will be reused.
	 * @var String
	 */
	protected $_scope = 'default';

	/**
	 * Parameters used for the constructor of the injector.
	 * @see registerParameter()
	 * @var Mixed[]
	 */
	protected $_parameters = array();

	/**
	 * If there is an instance of the object, and the object is shared. This
	 * is set to the shared instance of the object.
	 * @var Mixed
	 */
	protected $_unresolvedInstance;

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
	 * @param Enject_Scope $scope
	 * @param String $oldScopeId
	 * @see Enject_Scope_Value::cloneScope()
	 */
	function  cloneScope($scope, $oldScopeId)
	{
		if(isset($this->_instances[$oldScopeId]))
		{
			$instance = clone $this->_instances[$oldScopeId];
			$this->_instances[$scope->getScopeId()] = $instance;
		}
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
		$return = $this->_scope;
		if(is_string($return))
		{
			$return = $this->getContainer()->getScope($return);
		}
		return $return;
	}

	/**
	 * @return String[]
	 */
	function getTypes()
	{
		require_once 'Enject/Tools.php';
		$class = new ReflectionClass($this->getClassname());
		if($class->implementsInterface('Enject_Value')
				 && $this->getMode() != self::MODE_VALUE)
		{
			// TODO: see if there is a better way to resolve this
			$return = $this->_getUnresolvedInstance()->getTypes();
		}
		else
		{
			$return = Enject_Tools::getTypes($class);
		}
		return $return;
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
	 * Sets the name of the scope to use.
	 *
	 * A scope determines when/where an object is recreated. The default scope
	 * reuses all objects. The prototype scope reuses no objects
	 * @param String $scope
	 * @return Enject_Value_Builder
	 */
	function setScope($scope)
	{
		$this->_scope = $scope;
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
	 * @param Enject_Scope $scope
	 * @see Enject_Scope_Value::cloneScope()
	 */
	function removeScope($scope)
	{
		unset($this->_instances[$scope->getScopeId()]);
	}

	/**
	 * @return Mixed
	 * @uses getInjections()
	 * @uses Enject_Tools::inject()
	 */
	function resolve()
	{
		return $this->_resolve($this->_getUnresolvedInstance());
	}

	/**
	 * Builds the objects without doing any checks on the mode.
	 */
	function _getUnresolvedInstance()
	{
		$container = $this->getContainer();
		$build = true;
		$scopeId = false;
		$scope = $this->getScope();
		// a scope may be any object type
		// however, if it implements Enject_Scope, it may be reusable
		if($scope instanceOf Enject_Scope)
		{
			$scopeId = $scope->getScopeId();
			if(isset($this->_instances[$scopeId]))
			{
				$build = false;
				$return = $this->_instances[$scopeId];
			}
		}
		if($build)
		{
			require_once 'Enject/Tools.php';
			$className = $this->getClassname();
			$return = new $className;
			$injections = $this->getInjections($container);
			$return = Enject_Tools::inject($container, $return, $injections);
			$container->inject($return);
			if($scope instanceOf Enject_Scope)
			{
				$scope->registerValue($this);
				$this->_instances[$scopeId] = $return;
			}
		}
		return $return;
	}
}
