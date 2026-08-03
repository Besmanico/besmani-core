<?php

namespace Tests\Unit;

use App\Models\MainUser;
use App\Models\Referral;
use App\Policies\ReferralPolicy;
use App\Services\Referrals\ReferralAccessService;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ReferralPolicyTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
 
    public function test_personal_user_can_view_only_a_referral_they_created(): void
    {
        $user = $this->user(10, false);
        $policy = $this->policy(false);

        $this->assertTrue($policy->view($user, $this->referral(['referrer_user_id' => 10])));
        $this->assertFalse($policy->view($user, $this->referral(['referrer_user_id' => 11])));
    }

    public function test_personal_user_can_cancel_only_their_pending_outgoing_referral(): void
    {
        $user = $this->user(10, false);
        $policy = $this->policy(false);

        $this->assertTrue($policy->cancel($user, $this->referral([
            'referrer_user_id' => 10,
            'status' => 'pending',
        ])));
        $this->assertFalse($policy->cancel($user, $this->referral([
            'referrer_user_id' => 10,
            'status' => 'accepted',
        ])));
    }

    public function test_personal_user_cannot_accept_or_complete_referrals(): void
    {
        $user = $this->user(10, false);
        $policy = $this->policy(false);

        $this->assertFalse($policy->accept($user, $this->referral([
            'receiver_user_id' => 10,
            'status' => 'pending',
        ])));
        $this->assertFalse($policy->complete($user, $this->referral([
            'receiver_user_id' => 10,
            'status' => 'accepted',
        ])));
    }

    public function test_provider_can_accept_a_pending_referral_addressed_to_them(): void
    {
        $provider = $this->user(20, true);
        $policy = $this->policy(true);

        $this->assertTrue($policy->accept($provider, $this->referral([
            'receiver_user_id' => 20,
            'status' => 'pending',
        ])));
    }

    public function test_provider_can_complete_only_an_accepted_incoming_referral(): void
    {
        $provider = $this->user(20, true);
        $policy = $this->policy(true);

        $this->assertTrue($policy->complete($provider, $this->referral([
            'receiver_user_id' => 20,
            'status' => 'accepted',
        ])));
        $this->assertFalse($policy->complete($provider, $this->referral([
            'receiver_user_id' => 20,
            'status' => 'pending',
        ])));
    }

    public function test_provider_cannot_view_an_unrelated_referral(): void
    {
        $provider = $this->user(20, true);
        $policy = $this->policy(true, false);

        $this->assertFalse($policy->view($provider, $this->referral([
            'referrer_user_id' => 30,
            'receiver_user_id' => 40,
            'referrer_business_id' => 50,
            'receiver_business_id' => 60,
        ])));
    }

    public function test_provider_can_view_a_referral_connected_to_an_authorized_business(): void
    {
        $provider = $this->user(20, true);
        $policy = $this->policy(true, true);

        $this->assertTrue($policy->view($provider, $this->referral([
            'referrer_user_id' => 30,
            'receiver_user_id' => 40,
            'receiver_business_id' => 60,
        ])));
    }

    private function policy(bool $isProvider, bool $ownsBusiness = false): ReferralPolicy
    {
        /** @var ReferralAccessService&MockInterface $access */
        $access = Mockery::mock(ReferralAccessService::class);
        $access->shouldReceive('isProvider')->andReturn($isProvider);
        $access->shouldReceive('ownsBusiness')->andReturn($ownsBusiness);

        return new ReferralPolicy($access);
    }

    private function user(int $id, bool $isProvider): MainUser
    {
        return (new MainUser)->forceFill([
            'id' => $id,
            'service_pr' => $isProvider ? 1 : 0,
            'approved' => 1,
        ]);
    }

    private function referral(array $attributes): Referral
    {
        return (new Referral)->forceFill([
            'status' => 'pending',
            ...$attributes,
        ]);
    }
}
