<?php

namespace App\Enums\Enums;

enum TaskVisibility: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';
}
