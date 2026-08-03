<?php

namespace App\Enums;

enum TokenTransactionType: string
{
    case Earned = 'earned';
    case Given = 'given';
    case Settlement = 'settlement';
    case Adjustment = 'adjustment';
    case Reversal = 'reversal';
}
