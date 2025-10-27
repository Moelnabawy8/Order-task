<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class Order extends Model
{
    use HasApiTokens;
    use HasFactory;
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}