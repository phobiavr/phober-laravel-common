<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum ReservationStatusEnum: string {
    case QUEUE = 'QUEUE';
    case CANCELED = 'CANCELED';
    case APPROVED = 'APPROVED';
}
