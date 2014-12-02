<?php

namespace UmlGeneratorTests;

use UmlGenerator\UmlGenerator;
use UmlGenerator\UmlTemplate;

class ServiceProviderGeneratorTest extends \PHPUnit_Framework_TestCase
{
	public function testGenerator()
	{
		$umlGenerator = new UmlGenerator(new UmlTemplate());
		$umlGenerator->generate(['UmlGenerator\TestController']);
	}
}
