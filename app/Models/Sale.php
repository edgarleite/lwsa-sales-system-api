<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['seller_id', 'amount', 'sale_date'];
    protected $dates = ['deleted_at'];

    protected static function booted()
    {
        static::creating(function ($sale) {
            $sale->commission = $sale->amount * 0.085;
        });

        static::updating(function ($sale) {
            $sale->commission = $sale->amount * 0.085;
        });
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}