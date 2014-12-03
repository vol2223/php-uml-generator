<?php

namespace App\Command\Uml;

use DependencyGraph\DependencyResolver;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use UmlGenerator\UmlGenerator;

class UmlGenerateCommand extends Command
{

	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('dot:generate')
			->setDescription('uml generator')
			->addArgument('controllerName', InputArgument::REQUIRED, '起点となるコントローラーの指定');
	}

	/**
	 * @param   InputInterface   $input
	 * @param   OutputInterface  $output
	 * @return  void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dependencyResolver = new DependencyResolver();
		$dependencyResolver
			->registerDynamicType(Memcache::class)
			->registerNamedValue('connections');

		$umlGenerator = new UmlGenerator($dependencyResolver);

		if (!file_exists('uml')) {
			mkdir('uml' , 0755);
		}

		$controllerName = $input->getArgument('controllerName');
		if (!class_exists($controllerName)) {
			echo $className . 'クラスが存在しません。';
			exit(1);
		}
		foreach ($umlGenerator->generate($controllerName) as $umlTemplate) {
			file_put_contents('uml/' . $umlTemplate->getFileName() . '.dot', $umlTemplate->getTemplate());
		}
	}
}
