<?php

require __DIR__.'/../app/Support/OrganizationLevel.php';
require __DIR__.'/../app/Support/PermissionMode.php';
require __DIR__.'/../app/Support/ModuleLayer.php';
require __DIR__.'/../app/Support/ModuleCapability.php';
require __DIR__.'/../app/Support/ModuleCapabilityRegistry.php';
require __DIR__.'/../app/Support/DefaultModuleCapabilities.php';

use App\Support\DefaultModuleCapabilities;
use App\Support\OrganizationLevel;

$registry = DefaultModuleCapabilities::registry();
$errors = $registry->validationErrors();

if ($errors !== []) {
    fwrite(STDERR, json_encode($errors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).PHP_EOL);
    exit(1);
}

foreach (OrganizationLevel::values() as $level) {
    if ($registry->availableModules($level) === []) {
        fwrite(STDERR, "No modules are available for organization level: {$level}".PHP_EOL);
        exit(1);
    }
}

echo 'Core capability registry OK.'.PHP_EOL;
