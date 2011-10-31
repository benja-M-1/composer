<?php

namespace Composer\Command;

use Composer\Json\JsonFile;
use Composer\Package\Dumper\ArrayDumper;
use Composer\Package\MemoryPackage;
use Composer\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * JsonGeneratorCommand class
 *
 * @author Benjamin Grandfond <benjaming@theodo.fr>
 * @since 30/10/11
 */
class JsonGeneratorCommand extends Command
{
    public function configure()
    {
        $this->setName('generate-json')
            ->setDescription('Generates the composer.json file for your package.')
            ->addArgument('name', InputArgument::REQUIRED, 'The project name.')
            ->addArgument('version', InputArgument::REQUIRED, 'The version of the project.')
            ->addArgument('type', InputArgument::OPTIONAL, 'The ype of the project.', 'library')
            ->addOption('path', 'p', InputOption::VALUE_OPTIONAL, 'The path where to generate the .composer.json file.', getcwd())
            ->addOption('requires', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The required library of the project.', array());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = new JsonFile($input->getOption('path'));

        $package = new MemoryPackage($input->getArgument('name'), $input->getArgument('version'));
        $package->setType($input->getArgument('type'));
        $package->setRequires($input->getOption('requires'));

        $dumper = new ArrayDumper();

        $file->write($dumper->dump($package));
    }
}