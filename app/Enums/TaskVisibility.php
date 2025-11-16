<?php

namespace App\Enums;

enum TaskVisibility: string
{
    case PRIVATE = 'private';
    case PUBLIC = 'public';
}
