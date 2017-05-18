<?php

namespace Spc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

class PartialCommand extends Command
{
    /**
     * Gets the Sass directory from our composer.json spc_config option.
     *
     * @return string directory
     */
    protected function getSassDirectory()
    {
        $spmConfig = $this->getConfigJson();

        if ($sassDirectory = $spmConfig['sass_directory']) {
            return $sassDirectory;
        }

        return 'scss';
    }

    /**
     * Gets the stylesheet filename.
     *
     * @return string filename
     */
    protected function getStylesheetFilename()
    {
        $spmConfig = $this->getConfigJson();

        return ($spmConfig['stylesheet_filename']) ? $spmConfig['stylesheet_filename'] : 'styles.scss';
    }

    /**
     * Gets the spc_config data from our composer.json file.
     *
     * @return array
     */
    protected function getConfigJson()
    {
        $composerJson = json_decode(file_get_contents('composer.json'), true);

        if ($spmConfig = $composerJson['spm_config']) {
            return $composerJson['spm_config'];
        }

        throw new RuntimeException('Please set up spm_config in the composer.json file.');
    }

    protected function sortPartialsInStylesheet(OutputInterface $output, $stylesheet)
    {
        $output->writeln('<comment>Sorting partials...</comment>');

        $lines = file($stylesheet);

        sort($lines);

        $file = fopen($stylesheet, 'w') or die('Unable to open file: ' . $stylesheet);

        foreach ($lines as $line) {
            fwrite($file, $line);
        }

        fclose($file);

        $output->writeln('<info>Partials sorted!</info>');
    }
}
