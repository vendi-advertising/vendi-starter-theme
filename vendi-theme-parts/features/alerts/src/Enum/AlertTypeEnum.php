<?php

namespace Vendi\Theme\Feature\Alert\Enum;

enum AlertTypeEnum: string {
    case Info = 'info';
    case Warning = 'warning';
    case Critical = 'critical';
}
