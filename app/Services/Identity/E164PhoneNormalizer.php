<?php

namespace App\Services\Identity;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class E164PhoneNormalizer
{
    public function __construct(private ?PhoneNumberUtil $phoneNumbers = null)
    {
        $this->phoneNumbers ??= PhoneNumberUtil::getInstance();
    }

    /**
     * Return a validated E.164 number, or null when the input is invalid,
     * incomplete, or ambiguous without an explicit/default ISO region.
     */
    public function normalize(?string $value, ?string $region = null): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $isInternational = str_starts_with($value, '+') || str_starts_with($value, '00');
        $region = $region ? strtoupper(trim($region)) : null;
        if (! $isInternational && ! $region) {
            return null;
        }

        if (str_starts_with($value, '00')) {
            $value = '+'.substr($value, 2);
        }

        try {
            $number = $this->phoneNumbers->parse($value, $isInternational ? null : $region);
        } catch (NumberParseException) {
            return null;
        }

        if (! $this->phoneNumbers->isValidNumber($number)) {
            return null;
        }

        return $this->phoneNumbers->format($number, PhoneNumberFormat::E164);
    }
}
