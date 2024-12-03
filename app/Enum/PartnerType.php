<?php

namespace App\Enum;

use App\Traits\EnumParser;

enum PartnerType: string
{
    use EnumParser;
    case FACTORY = 'Factory';
    case SHIP = 'Ship';
}
