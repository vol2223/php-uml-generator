<?php

namespace UmlGenerator;

use DependencyGraph\DependencyResolver;

class UmlGenerator
{

	/**
	 * @var \UmlGenerator\UmlTemplate
	 */
	private $umlTemplate;

	/**
	 * execute uml generate
	 *
	 * @param string[] $entryPointClassNames
	 * @return \UmlGenerator\UmlTemplate[]
	 */
	public function generate($entryPointClassNames)
	{
		$dependencyResolver = new DependencyResolver();
		foreach ($entryPointClassNames as $entryPointClassName) {
			$umlDependencyObjectList = [];
			$dependencyGraph = $dependencyResolver->execute([$entryPointClassName]);
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
