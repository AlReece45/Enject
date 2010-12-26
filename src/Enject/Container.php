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
class Enject_Container
{
	/**
	 * Available (registered) components
	 * @var Mixed[]
	 * @see getComponent()
	 * @see registerComponent()
	 */
	protected $_components = array();

	/**
	 * @var Boolean
	 */
	protected $_defaultScopesRegistered = false;

	/**
	 * Enable the default scopes (Default and prototype)
	 * @var Boolean
	 */
	protected $_enableDefaultScopes = true;

	/**
	 * Available (registered) injectors
	 * @var Enject_Injector[]
	 * @see inject()
	 * @see registerInjector()
	 */
	protected $_injectors = array();

	/**
	 * Default injectors to use
	 * @see getInjector()
	 */
	protected $_defaultInjectors = array();

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
	 * @return Enject_Container
	 */
	function disableDefaultScopes()
	{
		$this->_enableDefaultScopes = false;
		return $this;
	}

	/**
	 * @return Enject_Container
	 */
	function enableDefaultScopes()
	{
		$this->_enableDefaultScopes = true;
		return $this;
	}

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
	 * Gets an instance
	 * @param String $className
	 * @return $class
	 * @throws Enject_Exception
	 * @uses $_injectors
	 */
	function inject($target)
	{
		$class = new ReflectionClass($target);
		foreach($this->_getTypeList($class) as $type)
		{
			$type = strtolower($type);
			if(isset($this->_injectors[$type]))
			{
				foreach($this->_injectors[$type] as $injector)
				{
					$injector->inject($this, $target);
				}
			}
		}
		return $this;
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Exception
	 * @uses $_components
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
	 * @param String $name
	 * @return Enject_Injector
	 * @uses $_injectors
	 * @uses Enject_Injector_Default
	 */
	function getInjector($name)
	{
		$name = strtolower($name);
		if(!isset($this->_defaultInjectors[$name]))
		{
			require_once 'Enject/Injector/Default.php';
			$this->_defaultInjectors[$name] = new Enject_Injector_Default();
			$this->registerInjector($name, $this->_defaultInjectors[$name]);
		}
		return $this->_defaultInjectors[$name];
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
	 */
	function getScope($scope = 'default')
	{
		// first check to see if the scope is already registered
		if(!isset($this->_scopes[$scope]))
		{
			// if the scope isn't registered, and default scopes are enabled
			// registere the default scopes
			if($this->_enableDefaultScopes && !$this->_defaultScopesRegistered)
			{
				require_once 'Enject/Scope/Default.php';
				$this->registerScope('default', new Enject_Scope_Default());
				$this->registerScope('prototype', new stdClass());
				$this->_defaultScopesRegistered = true;
			}
			if(!isset($this->_scopes[$scope]))
			{
				require_once 'Enject/Exception.php';
				throw new Enject_Exception('Scope [' . $scope
					 . '] is not registered');
			}
		}
		return $this->_scopes[$scope];
	}

	/**
	 * Registers a component (an easily reusable injection object)
	 * @param String $name
	 * @param Mixed $component
	 * @return Enject_Factory
	 * @see getComponent()
	 * @uses _registerComponent()
	 * @uses $_components
	 */
	function registerComponent($name, $component)
	{
		$this->_components[$name] = $component;
		$this->_registerComponent($component);
		return $this;
	}

	/**
	 * @param $className
	 * @param Enject_Injector $injector
	 * @see getInjector()
	 * @uses $_injectors
	 * @return Enject_Factory
	 */
	function registerInjector($typeName, $injector)
	{
		$typeName = strtolower($typeName);
		if(!isset($this->_injectors[$typeName]))
		{
			$this->_injectors[$typeName] = new SplObjectStorage();
		}
		$this->_injectors[$typeName]->attach($injector, $injector);
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
	 * @return Enject_Container
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
	 * @return Enject_Factory
	 */
	function registerType($type, $value)
	{
		$this->_types[$type] = $value;
		return $this;
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Exception
	 * @uses $_components
	 */
	function resolveComponent($name)
	{
		$name = strtolower($name);
		if(!isset($this->_components[$name]))
		{
			require_once 'Enject/Exception.php';
			throw new Enject_Exception("Component [$name] unavailable");
		}
		return $this->_components[$name];
	}

	/**
	 * @param String $type
	 * @return $type
	 * @throws Enject_Exception
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
			require_once 'Enject/Exception.php';
			throw new Enject_Exception('Unable to initialize an non-class'
				. '[' . $typeName . ']');
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
