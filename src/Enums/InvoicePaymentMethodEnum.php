<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum InvoicePaymentMethodEnum: string {
    case CASH = 'CASH';
    case CARD = 'CARD';
    case BONUS = 'BONUS';
}
