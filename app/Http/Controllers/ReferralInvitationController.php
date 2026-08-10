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
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->firstOrFail();

        if ($invitation->status === 'pending') {
            $invitation->update(['status' => 'opened']);
        }
        $request->session()->put('referral_invitation_id', $invitation->getKey());

        return redirect('/')->with('referral_invitation', 'You were invited to join Besmani. Create or sign in to your account to continue.');
    }
}
