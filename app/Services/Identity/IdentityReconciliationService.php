<?php

namespace App\Services\Identity;

use App\Models\CanonicalUser;
use App\Models\IdentityMergeCandidate;
use App\Repositories\LegacyEntityMapRepository;
use Illuminate\Support\Str;

class IdentityReconciliationService
{
    public function __construct(
        private LegacyEntityMapRepository $maps,
        private E164PhoneNormalizer $phones,
    ) {}

    public function detectCandidates(string $sourceSystem, string $sourceTable, string|int $sourceId, array $identity): array
    {
        $known = $this->maps->lookup($sourceSystem, $sourceTable, $sourceId, 'canonical_user');
        if ($known) {
            return ['explicit_mapping' => $known->target_id, 'candidates' => [], 'has_conflict' => false];
        }

        $phone = $this->normalizePhone(
            $identity['phone'] ?? null,
            $identity['phone_region'] ?? $identity['country_code'] ?? config('canonical.identity.default_phone_region'),
        );
        $email = $this->normalizeEmail($identity['email'] ?? null);
        if (! $phone && ! $email) {
            return ['explicit_mapping' => null, 'candidates' => [], 'has_conflict' => false];
        }

        $matches = CanonicalUser::query()->where(function ($query) use ($phone, $email): void {
            if ($phone) {
                $query->orWhere('phone_e164', $phone);
            }
            if ($email) {
                $query->orWhere('email_normalized', $email);
            }
        })->get();

        $candidates = [];
        foreach ($matches as $match) {
            $reasons = array_values(array_filter([
                $phone && $match->phone_e164 === $phone ? 'normalized_phone' : null,
                $email && $match->email_normalized === $email ? 'normalized_email' : null,
            ]));
            $score = count($reasons) === 2 ? 95 : 75;
            $candidates[] = IdentityMergeCandidate::firstOrCreate([
                'source_a_system' => $sourceSystem, 'source_a_table' => $sourceTable, 'source_a_id' => (string) $sourceId,
                'source_b_system' => 'core_v2', 'source_b_table' => 'canonical_users', 'source_b_id' => (string) $match->id,
            ], ['match_score' => $score, 'reasons_json' => $reasons, 'status' => 'pending_review']);
        }

        return [
            'explicit_mapping' => null,
            'candidates' => $candidates,
            'has_conflict' => count($candidates) > 1,
        ];
    }

    public function normalizeEmail(?string $email): ?string
    {
        $value = $email ? Str::lower(trim($email)) : null;

        return $value ?: null;
    }

    public function normalizePhone(?string $phone, ?string $region = null): ?string
    {
        return $this->phones->normalize($phone, $region);
    }
}
