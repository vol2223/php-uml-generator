<?php

namespace UmlGenerator;

class UmlDependencyObject
{

	/**
	 * @var string
	 */
	private $entryPointClassName;

	/**
	 * @var string[]
	 */
	private $dependencyObjectNames;

	/**
	 * @param string $entryPointClassName
	 * @param string[] $dependencyObjectNames
	 */
	public function __construct(
		$entryPointClassName,
		$dependencyObjectNames
	) {
		$this->entryPointClassName   = $entryPointClassName;
		$this->dependencyObjectNames = $dependencyObjectNames;
	}

	/**
	 * entry point class name getter
	 *
	 * @return string
	 */
	public function getEntryPointClassName()
	{
		return $this->entryPointClassName;
	}

	/**
	 * dependency class name getter
	 *
	 * @return string[]
	 */
	public function getDependenciesObjectNames()
	{
		return $this->dependencyObjectNames;
	}
}
