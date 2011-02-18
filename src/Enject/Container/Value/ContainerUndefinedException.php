<?php
/*
 * Enject Library
 * Copyright 2011 Alexander Reece
 * Licensed under: GNU Lesser Public License 2.1 or later
 *//**
 * @author Alexander Reece <AlReece45@gmail.com>
 * @copyright 2011 (c) Alexander Reece
 * @license http://www.opensource.org/licenses/lgpl-2.1.php
 * @package Enject
 */
require_once 'Enject/Exception.php';

/**
 * Exception thrown when an object needs its container set, but there's no
 * container set yet.
 */
class Enject_Container_Value_ContainerUndefinedException
	extends Enject_Exception
{
	/**
	 *@param String $message
	 */
	function __construct(
		$message = 'The container has not yet been defined',
		$code = 0,
		$previous = null
	)
	{
		parent::__construct($message, $code, $previous);
	}
}
