<?php

namespace Blog\App;

use Exception;

abstract class ConfigParser {

    /**
     * Parses a simple key-value text file and loads it into {@link $_ENV}
     * @param string $file
     * @return void
     * @throws Exception
     */
    public static function load(string $file): void {
        $contents = @file_get_contents($file);
        if(!$contents) throw new Exception('Config file does not exist or is empty');

        foreach(explode("\n", $contents) as $item) {
            $split = explode(' = ', $item, 2);
            if(count($split) !== 2) continue;
            $_ENV[$split[0]] = $split[1];
        }
    }

}