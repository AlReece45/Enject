<?php

require_once 'Enject/Blueprint.php';

class Enject_Blueprint_Default
	implements Enject_Blueprint
{
	/**
	 * Parameters used for the constructor of the target.
	 * @see getParameters()
	 * @see registerParameter()
	 * @var Mixed[]
	 */
	protected $_parameters = array();

	/**
	 * Injections to make on the object (via setters)
	 * @var Mixed[]
	 */
	protected $_properties = array();

	/**
	 * @var Mixed[][]
	 */
	protected $_injections = array();

	/**
	 * Adds an injection (a method call with parameters)
	 * @param String $method
	 * @param Mixed $parameters
	 * @see addProperty() properties override regular injections
	 * @return Enject_Blueprint_Default
	 */
	function addInjection($method, $parameters = array())
	{
		$method = strtolower($method);
		if(!isset($this->_injections[$method]))
		{
			$this->_injections[$method] = array();
		}
		$this->_injections[$method][] = $parameters;
		return $this;
	}

	/**
	 * Gets the methods to call after the object is created
	 * @see addInjection()
	 * @see setProperty()
	 * @uses $_properties
	 * @uses $_injections
	 * @return Mixed[]
	 */
	function getInjections( $container)
	{
		$return = $this->_injections;
		foreach($this->_properties as $property => $value)
		{
			$return['set' . $property] = array(array($value));
		}
		foreach($return as $methodName => $calls)
		{
			foreach($calls as $call => $parameters)
			{
				foreach($parameters as $parameterId => $parameter)
				{
					$updateParameter = false;
					while($parameter instanceOf Enject_Value)
					{
						$updateParameter = true;
						$parameter = $parameter->resolve($container);
					}
					if($updateParameter)
					{
						$return[$methodName][$call][$parameterId] = $parameter;
					}
				}
			}
		}
		return $return;
	}

	/**
	 * Gets the target parameters
	 * @see registerParameter()
	 * @uses $_parameters
	 * @return Mixed[]
	 */
	function getParameters()
	{
		return $this->_parameters;
	}

	/**
	 * Gets the target properties
	 * @see registerProperty()
	 * @uses $_properties
	 * @return Mixed[]
	 */
	function getProperties()
	{
		return $this->_properties;
	}

	/**
	 * Sets a property (a value set through setter methods)
	 * @param String $property
	 * @param Mixed $value
	 * @return Enject_Blueprint_Default
	 */
	function setProperty($property, $value)
	{
		$this->_properties[strtolower($property)] = $value;
		return $this;
	}
}