<?php

namespace Abdukhaligov\PhoberLaravelCommon\Enums;

enum NotificationProvider: string {
    case DISCORD = 'DISCORD';
    case TELEGRAM = 'TELEGRAM';
}
