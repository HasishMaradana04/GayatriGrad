<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Spatie\Permission\Models\Permission;

class AdminPermission extends Permission
{
    use LogsAdminActivity;
}
