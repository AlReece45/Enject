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

/**
 * Creates and manages objects.
 *
 * <p>First, the container manages objects. You can create an object any way you
 * want and register it with the container. A more robust way is getting an
 * object builder and telling it out to create the object.</p>
 *
 * <p>Second, the container manages injectors, injectors apply to classes or
 * interfaces they are useful for defining injections that aren't a part of
 * an specific injection.</p>
 */
class Enject_Container_Base
{
	/**
	 * Available (registered) components
	 * @var Mixed[]
	 * @see getComponent()
	 * @see registerComponent()
	 */
	protected $_components = array();

	/**
	 * Available (registered) injectors
	 * @var Enject_Container_Injector[]
	 * @see inject()
	 * @see registerInjector()
	 */
	protected $_injectors = array();

	/**
	 * All of the registered scopes
	 * @var stdClass[]
	 */
	protected $_scopes = array();

	/**
	 * Available (registered) types
	 * @var Mixed[]
	 * @see getComponent()
	 * @see registerComponent()
	 */
	protected $_types = array();

	/**
	 * Returns an object builder.
	 *
	 * Builders are not explicity shared, however you can assign a builder to
	 * a component and the object will be built when the component is requested
	 * @param String $className
	 * @return Enject_Builder_Default
	 */
	function getBuilder($className)
	{
		require_once 'Enject/Value/Builder.php';
		$return = new Enject_Value_Builder();
		$return->setContainer($this);
		$return->setClassname($className);
		return $return;
	}

	/**
	 * Gets a reference to a component (useful for injectors).
	 *
	 * The component does not need to exist when creating the reference, only
	 * when resolving the reference (often when injecting an object).
	 * @see resolveComponent() for the actual component
	 * @param String $name
	 * @return Mixed
	 */
	function getComponent($name)
	{
		require_once 'Enject/Value/Component.php';
		$return = new Enject_Value_Component();
		$return->setContainer($this);
		$return->setName($name);
		return $return;
	}

	/**
	 * Gets a injectior for a target type.
	 *
	 * The injector is already registered using {@link registerInjector()}
	 * @param String $name
	 * @return Enject_Injector
	 * @uses Enject_Injector_Container
	 */
	function getInjector($name)
	{
		$name = strtolower($name);
		if(!isset($this->_injectors[$name]))
		{
			require_once 'Enject/Container/Injector.php';
			$this->_injectors[$name] = new Enject_Container_Injector();
		}
		return $this->_injectors[$name];
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Exception
	 * @uses $_components
	 */
	function getType($type)
	{
		require_once 'Enject/Value/Type.php';
		$return = new Enject_Value_Type();
		$return->setContainer($this);
		$return->setType($type);
		return $return;
	}

	/**
	 * @param String $scope
	 * @return Enject_Scope
	 * @throws Enject_Exception
	 */
	function getScope($scope)
	{
		if(!isset($this->_scopes[$scope]))
		{
			require_once 'Enject/Container/ScopeUnavailableException.php';
			throw new Enject_Container_ScopeUnavailableException($scope);
		}
		return $this->_scopes[$scope];
	}

	/**
	 * Injects an object with all of the injectors that matches its types
	 *
	 * Injectors are applied in order from most-generic to most-specific.
	 * Interfaces are applied before classes. Parent classes are applied before
	 * Child Classes.
	 * @param Mixed $target
	 * @return Mixed
	 */
	function inject($target)
	{
		$class = new ReflectionObject($target);
		foreach($this->_getTypeList($class) as $type)
		{
			$this->getInjector($type)->inject($this, $target);
		}
		return $this;
	}

	/**
	 * Registers a component (an easily reusable injection object)
	 * @param String $name
	 * @param Mixed $component
	 * @return Enject_Container_Base
	 * @see getComponent()
	 * @see resolveComponent()
	 */
	function registerComponent($name, $component)
	{
		$this->_components[$name] = $component;
		$this->_registerComponent($component);
		return $this;
	}

	/**
	 * Registers an injector for a target type
	 * @param $typeName
	 * @param Enject_Injector $injector
	 * @return Enject_Container_Base
	 */
	function registerInjector($typeName, $injector)
	{
		$this->getInjector($typeName)->registerInjector($injector);
		return $this;
	}

	/**
	 * Registers a scope.
	 *
	 * Scopes are used by {@link Enject_Value_Builder} (or another type of
	 * {@link Enject_Value} to determine how to share objects. Scopes do not
	 * need to be an object or implement any interface. Only scopes that
	 * implement {@link Enject_Scope} allow for shared objects.
	 * @param String $name
	 * @param Mixed $scope
	 * @return Enject_Container_Base
	 */
	function registerScope($name, $scope)
	{
		$this->_scopes[$name] = $scope;
		return $this;
	}

	/**
	 * Forces $component to act for $type
	 *
	 * <p>Anyone overriding this method may need to be also override
	 * _registerComponent() because it uses checks this property directly/p>
	 * @param String $type
	 * @param Mixed $component
	 * @see getComponentByType()
	 * @uses $_types
	 * @return Enject_Container_Base
	 */
	function registerType($type, $value)
	{
		$this->_types[$type] = $value;
		return $this;
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Container_ComponentUnavailableException
	 * @uses $_components
	 */
	function resolveComponent($name)
	{
		$name = strtolower($name);
		if(!isset($this->_components[$name]))
		{
			require_once 'Enject/Container/ComponentUnavailableException.php';
			throw new Enject_Container_ComponentUnavailableException($name);
		}
		return $this->_components[$name];
	}

	/**
	 * @param String $type
	 * @return $type
	 * @throws Enject_Container_TypeUnavailableException
	 * @uses $_types
	 */
	function resolveType($typeName)
	{
		if(isset($this->_types[$typeName]))
		{
			$return = $this->_types[$typeName];
			if($return instanceOf Enject_Value)
			{
				$return = $return->resolve($this);
			}
		}
		elseif(class_exists($typeName))
		{
			$return = new $typeName();
			$this->inject($return);
		}
		else
		{
			require_once 'Enject/Container/TypeUnavailableException.php';
			throw new Enject_Container_TypeUnavailableException($typeName);
		}
		// return the expected value
		return $return;
	}

	/**
	 * Resolves the types that a component uses
	 * @param String $component
	 * @uses $_types
	 */
	protected function _registerComponent($component)
	{
		require_once 'Enject/Tools.php';
		foreach(Enject_Tools::getTypes($component) as $type)
		{
			if(!isset($this->_types[$type]))
			{
				$this->_types[$type] = $component;
			}
		}
	}

	/**
	 * Returns a type hierarchy in reverse order
	 * @param ReflectionClass $class
	 * @return String[]
	 */
	protected static function _getTypeList(ReflectionClass $class)
	{
		$return = $classes = $registeredTypes = array();
		// loop through the class heirarchy to gather all the injectors
		do
		{
			$className = $class->getName();
			if(!isset($registeredTypes[$className]))
			{
				$classes[] = $className;
				$registeredTypes[$className] = $class;
			}
		} while($class = $class->getParentClass());
		// loop through the classes in reverse order
		// to place the interfaces in the correct places
		foreach(array_reverse($classes) as $className)
		{
			$class = $registeredTypes[$className];
			foreach($class->getInterfaceNames() as $interfaceName)
			{
				if(!isset($registeredTypes[$interfaceName]))
				{
					$return[] = $interfaceName;
					$registeredTypes[$interfaceName] = true;
				}
			}
			$return[] = $className;
		}
		return $return;
	}
}
