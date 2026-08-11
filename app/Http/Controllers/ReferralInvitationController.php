<?php

namespace App\Http\Controllers;

use App\Models\ReferralInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReferralInvitationController extends Controller
{
    public function accept(Request $request, string $token): RedirectResponse
    {
        $invitation = ReferralInvitation::query()
            ->where('token_hash', hash('sha256', $token))
            ->where(function ($query): void {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->firstOrFail();

        if ($invitation->status === 'pending') {
            $invitation->update([
                'status' => 'opened',
            ]);
        }

        // Keep this until signup is completed
        $request->session()->put(
            'referral_invitation_id', 
            $invitation->getKey()
        );

        // These are only needed on the redirected homepage
        $request->session()->flash(
            'open_signup_modal',
            true
        );

        $request->session()->flash(
            'referral_invitation_party',
            $invitation->party
        );

        $recipient = $invitation->recipient_email
            ?: $invitation->recipient_phone
            ?: $invitation->recipient;

        $request->session()->flash(
            'referral_invitation_recipient',
            $recipient
        );

        return redirect('/');
    }
}