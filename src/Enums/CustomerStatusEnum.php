<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum CustomerStatusEnum: string {
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case VIP = 'VIP';
    case BLACKLIST = 'BLACKLIST';
}
