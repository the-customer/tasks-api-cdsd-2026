<?php

namespace App\Enums\Enums;

enum TaskRole: string
{
    case VIEWER = 'viewer';
    case EDITOR = 'editor';
    case DELETER = 'deleter';
    case OWNER = 'owner';
}
