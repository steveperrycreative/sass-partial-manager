<?php

namespace Spc;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakePartialCommand extends PartialCommand
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('make:partial')
             ->setDescription('Creates a new Sass partial and adds the import to the given stylesheet')
             ->setHelp('Set up an spm_config array in your composer.json file with sass_directory (defaults to "scss") and stylesheet_filename (defaults to "styles.scss") data.')
             ->addArgument('name', InputArgument::REQUIRED, 'The name for the partial, prefixes with an underscore.')
             ->addOption('type', null, InputOPTION::VALUE_OPTIONAL, 'Set a sub-folder for the partial.', 'components')
             ->addOption('sass_directory', null, InputOPTION::VALUE_OPTIONAL, 'Set the Sass directory.', $this->getSassDirectory());
    }

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<comment>Creating partial...</comment>');

        $partialName = $input->getArgument('name');
        $type = $input->getOption('type');
        $sassDirectory = getcwd() . DIRECTORY_SEPARATOR . $input->getOption('sass_directory');
        $partialDirectory = $sassDirectory . DIRECTORY_SEPARATOR . $type;
        $filename = '_' . $partialName . '.scss';

        if (!$this->directoryExists($partialDirectory)) {
            mkdir($partialDirectory);
        }

        $this->verifyPartialDoesNotExist($partialDirectory, $filename);

        $partialPathAndFilename = $partialDirectory . DIRECTORY_SEPARATOR . $filename;
        $file = fopen($partialPathAndFilename, 'w') or die('Unable to open file: ' . $partialPathAndFilename);
        fclose($file);

        $output->writeln('<info>Partial created!</info>');

        $stylesheet = $sassDirectory . DIRECTORY_SEPARATOR . $this->getStylesheetFilename();

        $this->addImportToStylesheet($output, $stylesheet, $type, $partialName);

        // TODO: add option in composer.json for optional sorting
        $this->sortPartialsInStylesheet($output, $stylesheet);
    }

    /**
     * Adds the import to the stylesheet.
     *
     * @param OutputInterface $output
     * @param                $stylesheet
     * @param                $type
     * @param                $partialName
     */
    private function addImportToStylesheet(OutputInterface $output, $stylesheet, $type, $partialName)
    {
        $output->writeln('<comment>Adding @import to stylesheet...</comment>');

        $file = fopen($stylesheet, 'a') or die('Unable to open file: ' . $stylesheet);

        fwrite($file, '@import "' . $type . DIRECTORY_SEPARATOR . $partialName . '";' . "\n");

        fclose($file);

        $output->writeln('<info>Import added to stylesheet!</info>');
    }

    /**
     * Stops us from overwriting existing partials.
     *
     * @param $partialDirectory
     * @param $filename
     */
    private function verifyPartialDoesNotExist($partialDirectory, $filename)
    {
        $file = $partialDirectory . DIRECTORY_SEPARATOR . $filename;

        if (is_file($file) || is_dir($file)) {
            throw new RuntimeException('File already exists!');
        }
    }

    /**
     * @param $directory
     *
     * @return bool
     */
    private function directoryExists($directory)
    {
        return is_dir($directory);
    }
}
