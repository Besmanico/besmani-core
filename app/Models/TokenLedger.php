<?php

namespace App\Models;

use App\Enums\TokenTransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenLedger extends Model
{
    use HasFactory;

    protected $table = 'token_ledger';

    protected $guarded = [];

    protected $casts = [
        'token_amount' => 'integer',
        'transaction_type' => TokenTransactionType::class,
    ];

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(MainUser::class, 'from_user_id');
    }

    public function fromBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'from_business_id');
    }

    public function toUser()
    {
        return $this->belongsTo(MainUser::class, 'to_user_id');
    }

    public function toBusiness()
    {
        return $this->belongsTo(InfoActivity::class, 'to_business_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(MainUser::class, 'created_by');
    }
}
