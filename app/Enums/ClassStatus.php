<?php

namespace App\Enums;

enum ClassStatus: string
{
    case Ongoing = 'ongoing';
    case Completed = 'completed';
    case Suspended = 'suspended';
}
