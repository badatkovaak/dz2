<?php

namespace App\Enum;

enum LinkExpirationType: int
{
    case Permanent = 1;
    case OneTime = 2;
    case ExpireByDate = 3;

    public static function fromString(string $input): ?LinkExpirationType
    {
        return match ($input) {
            'permanent' => LinkExpirationType::Permanent,
            'onetime' => LinkExpirationType::OneTime,
            'expirebydate' => LinkExpirationType::ExpireByDate,
            default => null,
        };
    }

    public function ToString(): string
    {
        return match ($this) {
            LinkExpirationType::Permanent => 'Permanent',
            LinkExpirationType::OneTime => 'OneTime',
            LinkExpirationType::ExpireByDate => 'ExpireByDate',
        };
    }
}
