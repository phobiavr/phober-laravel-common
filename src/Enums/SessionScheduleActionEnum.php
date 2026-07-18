<?php

namespace Phobiavr\PhoberLaravelCommon\Enums;

enum SessionScheduleActionEnum: string {
    case QUEUE = 'queue';
    case START = 'start';
    case CANCEL = 'cancel';
    case FINISH = 'finish';
}
