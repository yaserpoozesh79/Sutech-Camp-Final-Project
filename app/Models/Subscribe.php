<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subscribe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buy_date'
    ];
    protected $guarded = [
        'expire_date',
        'amount',
    ];
    protected $casts = [
        'buy_date' => 'datetime',
        'expire_date' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function create($data){
        return new Subscribe([
            'user_id' => $data['user_id'],
            'buy_date' => $data['buy_date'],
            'expire_date' => $data['expire_date'],
            'amount' => $data['amount']
        ]);
    }
}
