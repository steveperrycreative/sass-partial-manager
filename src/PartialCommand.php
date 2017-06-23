<?php

namespace Spc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Output\OutputInterface;

class PartialCommand extends Command
{
    /**
     * Gets the Sass directory from our composer.json spm_config option.
     *
     * @return string directory
     */
    protected function getSassDirectory()
    {
        $spmConfig = $this->getJsonConfig();

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
    protected function getStylesheetFilenameConfig()
    {
        $spmConfig = $this->getJsonConfig();

        return ($spmConfig['stylesheet_filename']) ? $spmConfig['stylesheet_filename'] : 'styles.scss';
    }

    /**
     * Gets the spm_config data from our composer.json file.
     *
     * @return array
     */
    protected function getJsonConfig()
    {
        $composerJson = json_decode(file_get_contents('composer.json'), true);

        if ($spmConfig = $composerJson['spm_config']) {
            return $composerJson['spm_config'];
        }

        throw new RuntimeException('Please set up spm_config in the composer.json file.');
    }

    /**
     * Gets the sort option.
     *
     * @return bool false
     */
    protected function getSortPartialsConfig()
    {
        $spmConfig = $this->getJsonConfig();

        return ($spmConfig['sort_imports']) ? $spmConfig['sort_imports'] : false;
    }

    /**
     * Sorts the import lines alphabetically.
     *
     * @param OutputInterface $output
     * @param                 $stylesheet
     */
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
