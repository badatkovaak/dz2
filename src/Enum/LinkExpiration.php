<?php

namespace App\Enum;

enum LinkExpiration: int
{
    case Permanent = 1;
    case OneTime = 2;
    case ExpireByDate = 3;
}
