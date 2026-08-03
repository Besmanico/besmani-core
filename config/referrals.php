<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Besmani COIN award
    |--------------------------------------------------------------------------
    |
    | BC is an internal, non-cash Besmani credit. It is awarded to the
    | referrer only after the destination Provider completes a referral.
    |
    */
    'completion_coin_award' => (int) env('REFERRAL_COMPLETION_COIN_AWARD', 100),
];
 