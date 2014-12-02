<?php

namespace UmlGenerator;

class UmlTemplate
{

	/**
	 * @var string
	 */
	private $dotFileName;

	/**
	 * @var \UmlGenerator\UmlDependencyObject[] $umlDependencyObjectList
	 */
	private $umlDependencyObjectList;

	/**
	 * @var string
	 */
	private $template;

	/**
	 * @var string
	 */
	private $classTemplate;

	/**
	 * @var string
	 */
	private $dependencyGraphTemplate;

	/**
	 * @var string[]
	 */
	private $writedClassNames = [];

	/**
	 * @param string $dotFileName
	 * @param \UmlGenerator\UmlDependencyObject[] $umlDependencyObjectList
	 */
	public function __construct($dotFileName, $umlDependencyObjectList)
	{
		$this->dotFileName = $dotFileName;
		$this->umlDependencyObjectList = $umlDependencyObjectList;
	}

	/**
	 * template build
	 */
	public function build()
	{
		$this->init();
		$this->titleWrite();
		
		foreach ($this->umlDependencyObjectList as $umlDependencyObject) {
			$this->entryPointclassWrite($umlDependencyObject);
			$this->classWrite($umlDependencyObject);
			$this->dependencyGraphWrite($umlDependencyObject);
		}

		$this->template .= $this->classTemplate . $this->dependencyGraphTemplate;
		$this->end();
		$this->template;
	}

	/**
	 * template getter
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * file name getter
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->dotFileName;
	}

	/**
	 * init
	 */
	private function init()
	{
		$this->template = <<<DOT
@startuml{{$this->dotFileName}.png}

DOT;
	}

	/**
	 * title
	 */
	private function titleWrite()
	{
		$this->template .= <<<DOT

title <size:18>{$this->dotFileName}</size>

DOT;
	}

	/**
	 * entry point class
	 */
	private function entryPointclassWrite($umlDependencyObject)
	{
		if (in_array($umlDependencyObject->getEntryPointClassName(), $this->writedClassNames)) {
			return;
		}
		$this->writedClassNames[] = $umlDependencyObject->getEntryPointClassName();

		$umlClassName = str_replace('\\', '', $umlDependencyObject->getEntryPointClassName());
		$methods = get_class_methods($umlDependencyObject->getEntryPointClassName());
		$this->classTemplate .= <<<DOT

class {$umlClassName} {
DOT;

		foreach ($methods as $method) {
		$this->classTemplate .= <<<DOT

  +{$method}()
DOT;
		}

		$this->classTemplate .= <<<DOT

}

DOT;
	}

	/**
	 * class
	 */
	private function classWrite($umlDependencyObject)
	{
		foreach ($umlDependencyObject->getDependenciesObjectNames() as $dependenciesObjectName) {
			if (in_array($dependenciesObjectName, $this->writedClassNames)) {
				continue;
			}
			$this->writedClassNames[] = $dependenciesObjectName;

			$umlClassName = str_replace('\\', '', $dependenciesObjectName);
			$methods = get_class_methods($dependenciesObjectName);
			$this->classTemplate .= <<<DOT

class {$umlClassName} {
DOT;

		foreach ($methods as $method) {
		$this->classTemplate .= <<<DOT

  +{$method}()
DOT;
		}

		$this->classTemplate .= <<<DOT

}

DOT;
		}
	}

	/**
	 * dependency graph
	 */
	private function dependencyGraphWrite($umlDependencyObject)
	{
		$umlParentClassName = str_replace('\\', '', $umlDependencyObject->getEntryPointClassName());
		foreach ($umlDependencyObject->getDependenciesObjectNames() as $dependenciesObjectName) {
			$umlClassName = str_replace('\\', '', $dependenciesObjectName);
			$this->dependencyGraphTemplate .= <<<DOT
{$umlParentClassName} --> {$umlClassName}

DOT;
		}
	}

	/**
	 * end
	 */
	private function end()
	{
		$this->template .= <<<DOT

@enduml
DOT;
	}
}
