<?php

namespace App\Enums;

enum QueueStatus: string
{
    case Waiting = 'WAITING';
    case Processing = 'PROCESSING';
    case Ready = 'READY';
    case Completed = 'COMPLETED';
    case Cancelled = 'CANCELLED';
}
