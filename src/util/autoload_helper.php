<?php
// src/util/autoload_helper.php

/**
 * Locate the Composer autoload file.
 *
 * @param string $currentDir The directory to start searching from.
 * @return string The path to the autoload file.
 */
function findComposerAutoload(string $currentDir = __DIR__): string
{
    $currentDir = rtrim($currentDir, DIRECTORY_SEPARATOR);
    $previousDir = '';

    while ($currentDir !== $previousDir) {
        $autoloadPath = $currentDir . '/vendor/autoload.php';
        if (file_exists($autoloadPath)) {
            return $autoloadPath;
        }

        $previousDir = $currentDir;
        $currentDir = dirname($currentDir);
    }

    throw new RuntimeException('Could not locate the Composer autoload file.');
}
