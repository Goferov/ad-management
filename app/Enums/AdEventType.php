<?php

namespace App\Enums;

enum AdEventType: string
{
    case Impression = 'impression';
    case Click      = 'click';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
