<?php

namespace App\Enums\Issue;

enum IssueStatus: string
{
    case OPEN        = 'open';
    case IN_PROGRESS = 'in_progress';
    case CLOSED      = 'closed';
}
