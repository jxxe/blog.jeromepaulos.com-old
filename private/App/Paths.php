<?php

namespace Blog\App;

abstract class Paths {

    public const ROOT = __DIR__ . '/../..';

    public const PRIVATE = self::ROOT . '/private';
    public const PUBLIC = self::ROOT . '/public';
    public const RESOURCES = self::ROOT . '/resources';

    public const CONFIG = self::ROOT . '/config.txt';

}