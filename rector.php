<?php

use Rector\Core\ValueObject\PhpVersion;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // paths to refactor; solid alternative to CLI arguments
    $rectorConfig->paths([__DIR__ . '/app', __DIR__ . '/tests',  __DIR__ . '/routes', __DIR__ . '/database']);

    // is your PHP version different from the one you refactor to? [default: your PHP version], uses PHP_VERSION_ID format
    $rectorConfig->phpVersion(PhpVersion::PHP_81);

    // Auto Import Names
    $rectorConfig->importNames();
};
