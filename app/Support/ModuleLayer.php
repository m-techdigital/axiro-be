<?php

namespace App\Support;

enum ModuleLayer: string
{
    case PlatformCore = 'platform_core';
    case BusinessCore = 'business_core';
    case OperationsAddon = 'operations_addon';
    case CustomerExtension = 'customer_extension';
}
