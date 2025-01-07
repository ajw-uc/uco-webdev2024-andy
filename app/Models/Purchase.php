<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PurchaseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'address',
        'payment'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'status' => PurchaseStatus::class,
        ];
    }
}
