<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum InvoiceStatusEnum: string {
    case QUEUE = 'QUEUE';
    case PAYED = 'PAYED';
    case CANCELED = 'CANCELED';
}
