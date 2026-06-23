<?php

namespace App\Enums\Issue;

enum IssuePriority: string
{
    case LOW    = 'low';
    case MEDIUM = 'medium';
    case HIGH   = 'high';
}
