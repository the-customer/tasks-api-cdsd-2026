<?php

namespace App\Enums\Enums;

enum TaskActivity: string
{
    case CREATED = 'created';
    case UPDATED = 'updated';
    case ARCHIVED = 'archived';
    case STATUS_CHANGER = 'status_changed';
    case RESTORED = 'restored';
    case SHARED_WITH = 'shared_with';
    case INVITATION_ACCEPTED = 'invitation_accepted';
    case VISIBILITY_CHANGED = 'visibility_changed';
}
