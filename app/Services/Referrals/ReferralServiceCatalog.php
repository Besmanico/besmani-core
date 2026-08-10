<?php

namespace App\Services\Referrals;

use App\Models\Activity;
use App\Models\ClinicService;
use App\Models\InfoActivity;
use App\Models\MenAcademyCourse;
use App\Models\MenSalonService;
use App\Models\ServiceReferralSetting;
use App\Models\WomenAcademyCourse;
use App\Models\WomenServiceSalon;
use Illuminate\Support\Collection;

class ReferralServiceCatalog
{
    public function forBusiness(InfoActivity $business): Collection
    {
        $activity = Activity::query()->find($business->activity_id);
        $source = mb_strtolower(trim((string) ($activity?->title_en ?? '')));
        $title = mb_strtolower(trim((string) ($activity?->title ?? '')));
        $userId = (int) $business->user_id;

        $services = (match (true) {
            $source === 'clinic_beauty' || $title === 'beauty clinic' => $this->clinicServices($userId),
            $source === 'women_salon' => $this->relatedServices(WomenServiceSalon::class, 'women_salon', $userId),
            $source === 'man_salon' => $this->relatedServices(MenSalonService::class, 'men_salon', $userId),
            $source === 'woman_learn' => $this->relatedServices(WomenAcademyCourse::class, 'women_academy', $userId),
            $source === 'man_learn' => $this->relatedServices(MenAcademyCourse::class, 'men_academy', $userId),
            default => collect(),
        })->filter(fn (array $service): bool => $service['title'] !== '')
            ->unique('key')
            ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return $this->withReferralSettings($services, $userId, (int) $business->getKey());
    }

    public function forUser(int $userId): Collection
    {
        $services = collect()
            ->concat($this->clinicServices($userId))
            ->concat($this->relatedServices(WomenServiceSalon::class, 'women_salon', $userId))
            ->concat($this->relatedServices(MenSalonService::class, 'men_salon', $userId))
            ->concat($this->relatedServices(WomenAcademyCourse::class, 'women_academy', $userId))
            ->concat($this->relatedServices(MenAcademyCourse::class, 'men_academy', $userId))
            ->filter(fn (array $service): bool => $service['title'] !== '')
            ->unique('key')
            ->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        return $this->withReferralSettings($services, $userId, null);
    }

    public function findForUser(int $userId, ?string $key): ?array
    {
        if ($key === null || $key === '') {
            return null;
        }

        return $this->forUser($userId)->firstWhere('key', $key);
    }

    public function findForBusiness(InfoActivity $business, ?string $key): ?array
    {
        if ($key === null || $key === '') {
            return null;
        }

        return $this->forBusiness($business)->firstWhere('key', $key);
    }

    private function clinicServices(int $userId): Collection
    {
        return ClinicService::query()
            ->with('clinic')
            ->where('user_id', $userId)
            ->where('active', 1)
            ->get()
            ->map(fn (ClinicService $service): array => $this->option(
                'clinic',
                (int) $service->getKey(),
                (string) ($service->clinic?->title ?? $service->title ?? ''),
                null
            ));
    }

    private function relatedServices(string $model, string $type, int $userId): Collection
    {
        return $model::query()
            ->with('service')
            ->where('user_id', $userId)
            ->get()
            ->map(fn ($assignment): array => $this->option(
                $type,
                (int) $assignment->getKey(),
                (string) ($assignment->service?->title ?? $assignment->title ?? ''),
                null
            ));
    }

    private function option(
        string $type,
        int $id,
        string $title,
        mixed $bc = null
    ): array {
        return [
            'key' => $type.':'.$id,
            'type' => $type,
            'id' => $id,
            'title' => trim($title),
            'bc' => $bc,
        ];
    }

    private function withReferralSettings(Collection $services, int $providerUserId, ?int $businessId): Collection
    {
        if ($services->isEmpty()) {
            return $services;
        }

        $settings = ServiceReferralSetting::query()
            ->where('provider_user_id', $providerUserId)
            ->get()
            ->keyBy(fn (ServiceReferralSetting $setting): string => $setting->service_type.':'.$setting->service_id);

        return $services->map(function (array $service) use ($settings, $businessId): array {
            /** @var ServiceReferralSetting|null $setting */
            $setting = $settings->get($service['key']);
            if ($setting && $setting->business_id !== null && (int) $setting->business_id !== $businessId) {
                $setting = null;
            }

            return array_merge($service, [
                'bc' => $setting && $setting->enabled ? (int) $setting->reward_bc : 0,
                'reward_bc' => $setting && $setting->enabled ? (int) $setting->reward_bc : 0,
                'discount_type' => $setting && $setting->enabled ? $setting->discount_type : 'none',
                'discount_value' => $setting && $setting->enabled ? (float) $setting->discount_value : 0,
                'discount_currency' => $setting && $setting->enabled ? $setting->discount_currency : null,
            ]);
        })->values();
    }
}
