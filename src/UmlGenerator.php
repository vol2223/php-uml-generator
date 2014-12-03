<?php

namespace UmlGenerator;

use DependencyGraph\DependencyResolver;

class UmlGenerator
{

	/**
	 * @var DependencyGraph\DependencyResolver;
	 */
	private $dependencyResolver;

	/**
	 * @var \UmlGenerator\UmlTemplate
	 */
	private $umlTemplate;

	/**
	 * @param DependencyGraph\DependencyResolver;
	 */
	public function __construct(DependencyResolver $dependencyResolver)
	{
		$this->dependencyResolver = $dependencyResolver;
	}

	/**
	 * execute uml generate
	 *
	 * @param string[] $entryPointClassNames
	 * @return \UmlGenerator\UmlTemplate[]
	 */
	public function generate($entryPointClassNames)
	{
		$entryPointClassNames = is_array($entryPointClassNames) ? $entryPointClassNames : [$entryPointClassNames];
		foreach ($entryPointClassNames as $entryPointClassName) {
			$umlDependencyObjectList = [];
			$dependencyGraph = $this->dependencyResolver->execute([$entryPointClassName]);
			foreach ($dependencyGraph->getAllObjects() as $object) {
				if ($object->getValue()->isDynamic()) {
					continue;
				}
				$parentClassName = $object->getValue()->getType()->getName();
				$dependencyObjectNames = [];
				foreach ($object->getDependencies() as $dependency) {
					$dependencyObjectNames[] = $dependency->getValue()->getType()->getName();
				}
				$umlDependencyObjectList[] = new UmlDependencyObject(
					$parentClassName,
					$dependencyObjectNames
				);
			}

			$template = new UmlTemplate(str_replace('\\', '', $entryPointClassName), $umlDependencyObjectList);
			$template->build();
			$templateList[] = $template;
		}

		return isset($templateList) ? $templateList : [];
	}
}
