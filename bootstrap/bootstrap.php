<?php

use Symfony\Component\Yaml\Yaml;

$configFile = __DIR__ . '/../config/config.yaml';
if (!file_exists($configFile)) {
    throw new RuntimeException("Missing config.yaml for integration tests.");
}
$GLOBALS['googleApiConfig'] = Yaml::parseFile($configFile);
