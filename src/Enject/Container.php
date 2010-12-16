<?php

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
	 * Available (registered) components
	 * @var Enject_Target[]
	 * @see getTarget()
	 * @see registerTarget()
	 */
	protected $_targets = array();

	/**
	 * Available (registered) components
	 * @var Mixed[]
	 * @see getComponent()
	 * @see registerComponent()
	 */
	protected $_types = array();

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
		$return->setClassname($className);
		return $return;
	}

	/**
	 * Gets an instance
	 * @param String $className
	 * @return $class
	 * @throws Enject_Exception
	 * @uses $_targets
	 */
	function getInstance($className)
	{
		$class = $instanceClass = new ReflectionClass($className);
		$interfaces = $parameters = array();
		$classTargets = $interfaceTargets = array();

		// loop through the class heirarchy to gather all the targets
		do
		{
			$currentClassName = strtolower($class->getName());
			// check to see if there's a target for this class name
			if(isset($this->_targets[$currentClassName]))
			{
				$target = $this->_targets[$currentClassName];
				$classTargets[] = array($currentClassName, $target);
				$classParameters = array();
				// parameters in PHP are usually case sensitive, everywhere
				// else in our framework, because of the way methods work for
				// method based-injection, they are not case-sensitive
				// here: we emulate the case-insensitivity
				foreach($target->getParameters() as $name => $parameter)
				{
					$classParameters[strtolower($name)] = $parameter;
				}
				$parameters = array_merge($classParameters, $parameters);
			}
			// get any targets targeting interfaces
			// we want to make sure to only apply interfaces in the correct
			// order. (they shouldn't all be applied last)
			$interfaceTargets[$currentClassName] = array();
			foreach($class->getInterfaceNames() as $interfaceName)
			{
				$interfaceName = strtolower($interfaceName);
				if(isset($this->_targets[$interfaceName]))
				{
					$interfaceTargets[$currentClassName][] = array(
						$interfaceName,
						$this->_targets[$interfaceName],
					);
				}
			}
		} while($class = $class->getParentClass());

		//  finally, create the object
		if($parameters)
		{
			$constructor = $instanceClass->getConstructor();
			if($constructor)
			{
				require_once 'Enject/Tools.php';
				$args = Enject_Tools::prepareArguments($constructor, $parameters);
				$instance = $class->newInstanceArgs($className, $args);
			}
			else
			{
				$instance = new $className;
			}
		}
		else
		{
			$instance = new $className;
		}

		// go through all the targets (in order of the class heirarchy) and
		// instruct them to inject the newly created object
		foreach(array_reverse($classTargets) as $classTargetPart)
		{
			list($className, $classTarget) = $classTargetPart;
			// get the interfaces first, we want to apply interfaces before
			// they're used in the highest-level class.
			if(isset($interfaceTargets[$className]))
			{
				foreach($interfaceTargets[$className] as $interfacePart)
				{
					list($interfaceName, $interfaceTarget) = $interfacePart;
					// don't inject the interface if a parent has already done so
					if(!isset($interfaces[$interfaceName]))
					{
						$interfaceTarget->inject($instance);
					}
				}
			}
			// the class is more specific than the interfaces
			// so we inject it after the interfaces
			$classTarget->inject($instance);
		}
		return $instance;
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Exception
	 * @uses $_components
	 */
	function getComponent($name)
	{
		return $this->getInstance('Enject_Value_Component')->setComponent($name);
	}

	/**
	 * @param String $name
	 * @return Enject_Target
	 * @uses $_targets
	 * @uses Enject_Target_Default
	 */
	function getTarget($name)
	{
		$name = strtolower($name);
		if(!isset($this->_targets[$name]))
		{
			require_once 'Enject/Target/Default.php';
			$this->_targets[$name] = new Enject_Target_Default();
			$this->_targets[$name]->setContainer($this);
		}
		return $this->_targets[$name];
	}

	/**
	 * @param String $name
	 * @return Mixed
	 * @throws Enject_Exception
	 * @uses $_components
	 */
	function getType($name)
	{
		return $this->getInstance('Enject_Value_Type')->setType($name);
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
	 * @param Enject_Target $target
	 * @see getTarget()
	 * @uses $_targets
	 * @return Enject_Factory
	 */
	function registerTarget($typeName, Enject_Target $target)
	{
		$this->_targets[$typeName] = $target;
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
			$return = $this->getInstance($typeName);
		}
		else
		{
			throw new Enject_Exception('Unable to initialize an non-class'
				. '[' . $typeName . ']');
		}
		// return the expected value
		return $return;
	}
}