<?php

namespace App\Contracts\Notifications;

interface NotificationProvider
{
    public function send(string $channel, string $recipient, string $subject, string $message): void;
}
