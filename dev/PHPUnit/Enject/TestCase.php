<?php
/*
 * Enject Library Tests
 * Copyright 2010 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2010 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Test_Enject
 */

/*
 * Provides a base for al of the other tests.
 */
abstract class Test_Enject_TestCase
	extends PHPUnit_Framework_TestCase
{
	/**
	 * Loads a file for the class name if it exists on the include_path
	 * @param String $class
	 */
	protected function _loadClass($class)
	{
		if(strpos($class, 'Test_') === 0)
		{
			$class = substr($class, 5);
		}
		$file = strtr($class, "_", "/");
		foreach(explode(PATH_SEPARATOR, get_include_path()) as $path)
		{
			$filePath = "{$path}/{$file}.php";
			if(file_exists($filePath))
			{
				include_once $filePath;
				break;
			}
		}
	}

	/**
	 * @param String $class
	 * @return Boolean
	 */
	function assertClassExists($class)
	{
		if(!class_exists($class))
		{
			$this->_loadClass($class);
		}
		$this->assertTrue(class_exists($class), "Class $class does not exist");
		return $this;
	}

	/**
	 * @param String $class
	 * @return Boolean
	 */
	function assertInterfaceExists($class)
	{
		if(!interface_exists($class))
		{
			$this->_loadClass($class);
		}
		$this->assertTrue(interface_exists($class), "Interface $class does not exist");
		return $this;
	}

	/**
	 * @param Mixed $value 
	 */
	function assertTraversable($value)
	{
		if(is_object($value))
		{
			$this->assertType('Iterator', $value);
		}
		else
		{
			$this->assertType('array', $value);
		}
	}
}
