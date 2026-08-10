<?php

namespace Tests\Unit;

use App\Services\Notifications\CustomerIoNotificationProvider;
use RuntimeException;
use Tests\TestCase;

class CustomerIoNotificationProviderTest extends TestCase
{
    public function test_missing_credentials_fail_gracefully_without_exposing_a_secret(): void
    {
        config()->set('services.customerio.app_api_key', '');
        config()->set('services.customerio.invitation_email_transactional_id', '');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Customer.io invitation delivery is not configured');

        (new CustomerIoNotificationProvider)->send('email', 'invitee@example.com', 'Invite', 'Join Besmani');
    }
}
