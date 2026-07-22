<?php

namespace App\Support;

enum PermissionMode: string
{
    case HiddenFull = 'hidden_full';
    case SimpleRoles = 'simple_roles';
    case ScopedPermissions = 'scoped_permissions';
}
