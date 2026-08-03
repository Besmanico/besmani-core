<?php

namespace Tests\Unit;

use App\Models\MainUser;
use App\Services\Referrals\ReferralAccessService;
use PHPUnit\Framework\TestCase;

class ReferralAccessServiceTest extends TestCase
{
    public function test_service_pr_identifies_a_provider_even_when_approval_is_not_set(): void
    {
        $provider = (new MainUser)->forceFill([
            'service_pr' => 1,
            'approved' => null,
        ]);

        $this->assertTrue((new ReferralAccessService)->isProvider($provider));
    }

    public function test_personal_user_is_not_identified_as_a_provider(): void
    {
        $user = (new MainUser)->forceFill([
            'service_pr' => 0,
            'approved' => 1,
        ]);

        $this->assertFalse((new ReferralAccessService)->isProvider($user));
    }
}
