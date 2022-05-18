<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'charge_date'
    ];

    protected $guarded = [

    ];

    protected $casts = [
        'charge_date' => 'datetime'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public static function create($data){
        return new Charge([
            'user_id'=>$data['user_id'],
            'amount'=>$data['amount'],
            'charge_date'=>$data['charge_date']
        ]);
    }
}
