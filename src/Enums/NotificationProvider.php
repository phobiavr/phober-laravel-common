<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum NotificationProvider: string {
    case DISCORD = 'DISCORD';
    case TELEGRAM = 'TELEGRAM';
}
