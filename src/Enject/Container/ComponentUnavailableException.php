<?php
/*
 * Enject Library
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <alreece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Exception.php';

/**
 * Exception thrown when {@link Enject_Container_Base::resolveComponent()} is
 * called when a component is unavailable (usually not defined yet).
 */
class Enject_Container_ComponentUnavailableException
	extends Enject_Exception
{
	/**
	 * @param String $typeName
	 * @param Integer $code
	 * @param Exception $previous
	 */
	function __construct($component, $code = null, $previous = null)
	{
		$message = "Component [$component] not available in container";
		parent::__construct($message, $code, $previous);
	}
}
