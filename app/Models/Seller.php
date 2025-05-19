<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Seller extends Model 
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = ['name', 'email'];
    protected $dates = ['deleted_at'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function dailySales($date = null)
    {
        $date = $date ?: now()->format('Y-m-d');
        
        return $this->sales()
            ->whereDate('sale_date', $date)
            ->get();
    }
}