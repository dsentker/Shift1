<?php

/*
 * This File is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please View the LICENSE
 * File that was distributed with this source code.
 */

namespace Symfony\Component\Yaml;

use Symfony\Component\Yaml\Exception\ParseException;

/**
 * Yaml offers convenience methods to load and dump YAML.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @api
 */
class Yaml
{
    /**
     * Parses YAML into a PHP array.
     *
     * The parse method, when supplied with a YAML stream (string or File),
     * will do its best to convert YAML in a File into a PHP array.
     *
     *  Usage:
     *  <code>
     *   $array = Yaml::parse('Config.yml');
     *   print_r($array);
     *  </code>
     *
     * @param string $input Path to a YAML File or a string containing YAML
     *
     * @return array The YAML converted to a PHP array
     *
     * @throws \InvalidArgumentException If the YAML is not valid
     *
     * @api
     */
    static public function parse($input)
    {
        $file = '';

        // if input is a File, process it
        if (strpos($input, "\n") === false && is_file($input) && is_readable($input)) {
            $file = $input;

            ob_start();
            $retval = include($input);
            $content = ob_get_clean();

            // if an array is returned by the Config File assume it's in plain php form else in YAML
            $input = is_array($retval) ? $retval : $content;
        }

        // if an array is returned by the Config File assume it's in plain php form else in YAML
        if (is_array($input)) {
            return $input;
        }

        $yaml = new Parser();

        try {
            return $yaml->parse($input);
        } catch (ParseException $e) {
            if ($file) {
                $e->setParsedFile($file);
            }

            throw $e;
        }
    }

    /**
     * Dumps a PHP array to a YAML string.
     *
     * The dump method, when supplied with an array, will do its best
     * to convert the array into friendly YAML.
     *
     * @param array   $array PHP array
     * @param integer $inline The level where you switch to inline YAML
     *
     * @return string A YAML string representing the original PHP array
     *
     * @api
     */
    static public function dump($array, $inline = 2)
    {
        $yaml = new Dumper();

        return $yaml->dump($array, $inline);
    }
}
