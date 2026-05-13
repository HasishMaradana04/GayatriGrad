<?php

namespace App\Models;

use App\Models\Concerns\LogsAdminActivity;
use Spatie\Permission\Models\Role;

class AdminRole extends Role
{
    use LogsAdminActivity;
}
