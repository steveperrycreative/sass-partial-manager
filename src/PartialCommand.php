<?php

namespace Spc;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;

class PartialCommand extends Command
{
    /**
     * Gets the Sass directory from our composer.json spc_config option.
     *
     * @return string directory
     */
    protected function getSassDirectory()
    {
        $spc_config = $this->getConfigJson();

        if ($sassDirectory = $spc_config['sass_directory']) {
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
        $spc_config = $this->getConfigJson();

        return ($spc_config['stylesheet_filename']) ?: 'styles.scss';
    }

    /**
     * Gets the spc_config data from our composer.json file.
     *
     * @return array
     */
    protected function getConfigJson()
    {
        $composerJson = json_decode(file_get_contents('composer.json'), true);

        if ($config = $composerJson['spc_config']) {
            return $composerJson['spc_config'];
        }

        throw new RuntimeException('Please set up spc_config in the composer.json file.');
    }
}
