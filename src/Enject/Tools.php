<?php

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
	static function prepareArguments(
		ReflectionFunctionAbstract $method,
		$parameters
	)
	{
		// if the array is a "perfect" numerically indexed array
		// assume this is expected and simply use it
		if(array_values($parameters) == $parameters)
		{
			return $parameters;
		}
		
		$return = array();
		// emulate case-insenstivity (as method names and classs names are not
		// case sensitive). Keep the case-insensitive consistancy. 
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
				throw new Enject_Exception('Missing parameter ['
					. $parameter->getName() . '] for method ['
					. $method->getName() . ']');
			}
		}
		return $return;
	}

	/**
	 * Executes injections into an object
	 * @param Mixed $object
	 * @param Mixed[][] $injections
	 * @return $object
	 */
	static function inject($object, $injections)
	{
		foreach($injections as $methodName => $calls)
		{
			$method = new ReflectionMethod($object, $methodName);
			foreach($calls as $parameters)
			{ 
				if(count($parameters) > 1)
				{
					$parameters = self::prepareArguments($method, $parameters);
				}
				else
				{
					$parameters = array_values($parameters);
				}
				$method->invokeArgs($object, $parameters);
			}
		}
		return $object;
	}

	static function getTypes($object)
	{
		$return = array();
		if($object instanceOF Enject_Value)
		{
			foreach($object->getTypes() as $type)
			{
				$return[$type] = $type;
			}
		}
		else
		{
			if(is_string($object))
			{
				$class = new ReflectionClass($object);
			}
			elseif($object instanceOf ReflectionClass)
			{
				$class = $object;
			}
			else
			{
				$class = new ReflectionObject($object);
			}
			foreach($class->getInterfaceNames() as $interface)
			{
				$return[$interface] = $interface;
			}
			do
			{
				$className = $class->getName();
				$return[$className] = $className;
			} while($class = $class->getParentClass());
		}
		return $return;
	}
}