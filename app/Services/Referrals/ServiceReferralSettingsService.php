<?php

namespace App\Services\Referrals;

use App\Models\ClinicService;
use App\Models\MainUser;
use App\Models\MenAcademyCourse;
use App\Models\MenSalonService;
use App\Models\ServiceReferralSetting;
use App\Models\WomenAcademyCourse;
use App\Models\WomenServiceSalon;
use Illuminate\Validation\ValidationException;

class ServiceReferralSettingsService
{
    private const MODELS = [
        'clinic' => ClinicService::class,
        'women_salon' => WomenServiceSalon::class,
        'men_salon' => MenSalonService::class,
        'women_academy' => WomenAcademyCourse::class,
        'men_academy' => MenAcademyCourse::class,
    ];

    public function update(MainUser $actor, string $type, int $id, array $data, ReferralAccessService $access): ServiceReferralSetting
    {
        $model = self::MODELS[$type] ?? null;
        if (! $model) {
            throw ValidationException::withMessages(['service' => 'Unknown service type.']);
        }
        $service = $model::query()->whereKey($id)->where('user_id', $actor->getKey())->first();
        if (! $access->isProvider($actor) || ! $service) {
            abort(403);
        }
        $businessId = $data['business_id'] ?? null;
        if ($businessId !== null && ! $access->ownsBusiness($actor, (int) $businessId)) {
            abort(403);
        }

        return ServiceReferralSetting::query()->updateOrCreate(
            ['service_type' => $type, 'service_id' => $id],
            [
                'provider_user_id' => $actor->getKey(),
                'business_id' => $businessId,
                'enabled' => (bool) $data['enabled'],
                'reward_bc' => (int) ($data['reward_bc'] ?? 0),
                'discount_type' => $data['discount_type'] ?? 'none',
                'discount_value' => ($data['discount_type'] ?? 'none') === 'none' ? 0 : (float) ($data['discount_value'] ?? 0),
                'discount_currency' => ($data['discount_type'] ?? 'none') === 'fixed' ? ($data['discount_currency'] ?? config('app.currency', 'USD')) : null,
            ]
        );
    }
}
