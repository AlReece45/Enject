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
 * Methods that are used in several different classes
 */
class Enject_Tools
{
	/**
	 * Takes an argument list (with arguments by name) and converts it to a
	 * numerically numbered array. Suitable for use with functions like
	 * func_call_args_array() or newInstanceArgs()
	 * @param ReflectionFunctionAbstract $method
	 * @param Mixed[] $parameters
	 * @return Mixed[]
	 */
	static function prepareArguments($method, $parameters)
	{
		$parameterValues = array_values($parameters);
		// if the array is a "perfect" numerically indexed array
		// assume this is expected and simply use it
		if($parameterValues == $parameters)
		{
			$return = $parameters;
		}
		elseif(!isset($parameterValues[1]))
		{
			$return = $parameterValues;
		}
		else
		{
			// emulate case-insenstivity (as method names and classs names are
			// not case sensitive). Keep the case-insensitive consistancy.
			$return = array();
			$parameters = array_change_key_case($parameters, CASE_LOWER);

			// finally, do the main loop.
			foreach($method->getParameters() as $parameter)
			{
				// emulate case-insensitivity
				$parameterName = strtolower($parameter->getName());
				// check to see if we've set it
				if(isset($parameters[$parameterName]))
				{
					$return[] = $parameters[$parameterName];
				}
				// if the parameter is not optional, thrown an error
				// because the user (developer) probably made a mistake
				elseif(!$parameter->isOptional())
				{
					require_once 'Enject/Exception.php';
					throw new Enject_Exception('Missing parameter ['
						. $parameter->getName() . '] for method ['
						. $method->getName() . ']');
				}
			}
		}
		return $return;
	}

	/**
	 * Executes injections into an object
	 * @param Mixed $object
	 * @param Enject_Injection[] $injections
	 * @return $object
	 */
	static function inject($container, $object, $injections)
	{
		foreach($injections as $injection)
		{
			$methodName = $injection->getMethod();
			$parameters = $injection->getParameters();

			// go through each parameter resolving Enject_Value parameters
			// as they happen
			foreach($parameters as $k => $v)
			{
				if($v instanceOf Enject_Value)
				{
					$parameters[$k] = $v->resolve($container);
				}
			}
			// figure out how to pass the arguments
			try
			{
				$method = new ReflectionMethod($object, $methodName);
				$parameters = self::prepareArguments($method, $parameters);
				$method->invokeArgs($object, $parameters);
			}
			catch(ReflectionException $e)
			{
				// if there's a magic method, attempt to use that
				if(method_exists($object, '__call'))
				{
					// they have one defined, however we can't do much
					// smart things with the parameter by name :(
					$parameters = array_values($parameters);
					$callback = array($object, $methodName);
					call_user_func_array($callback, $parameters);
				}
				else
				{
					// not much we can do :(
					throw $e;
				}
			}
		}
		return $object;
	}

	/**
	 * Returns an array of all the types that apply to an object (classes and
	 * interfaces) in one list.
	 * @param Mixed|String $reflector Object or classname to get the types of
	 * @return String[]
	 */
	static function getTypes($reflector)
	{
		$return = array();
		
		// if the object isn't already a reflector, make it one
		if(!$reflector instanceOf ReflectionClass)
		{
			if(is_string($reflector))
			{
				$reflector = new ReflectionClass($reflector);
			}
			else
			{
				$reflector = new ReflectionObject($reflector);
			}
		}

		// get the list of interfaces and add it to the return
		foreach($reflector->getInterfaceNames() as $interface)
		{
			$return[$interface] = $interface;
		}

		// finally, use the reflector to go up the list of parents
		do
		{
			$className = $reflector->getName();
			$return[$className] = $className;
		} while($reflector = $reflector->getParentClass());
		
		return $return;
	}
}
