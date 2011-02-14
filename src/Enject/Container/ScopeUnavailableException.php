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
 * Exception thrown when {@link Enject_Container_Base::getScope()} is called
 * on a unavailable scope
 */
class Enject_Container_ScopeUnavailableException
	extends Enject_Exception
{
	/**
	 * @param String $scopeName
	 * @param Integer $code
	 * @param Exception $previous
	 */
	function __construct($scopeName, $code = null, $previous = null)
	{
		$message = "Scope [$scopeName] not available in container";
		parent::__construct($message, $code, $previous);
	}
}
