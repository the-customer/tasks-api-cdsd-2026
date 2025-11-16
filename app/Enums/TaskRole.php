<?php

namespace App\Enums;

enum TaskRole: string
{
    case VIEWER = 'viewer';
    case EDITOR = 'editor';
    case DELETER = 'deleter';
    case OWNER = 'owner';
}
