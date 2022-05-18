<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donate extends Model
{
    use HasFactory;

    protected $fillable = [
        'donator',
        'donatee',
        'amount',
        'donate_date'
    ];

    protected $guarded = [

    ];

    protected $casts = [
        'donate_date' => 'datetime'
    ];

    public function donater(){
        return $this->belongsTo(User::class,'donator');
    }
    public function donatee(){
        return $this->belongsTo(User::class,'donatee');
    }

    public static function create($data){
        return new Donate([
            'donator' => $data['donator'],
            'donatee' => $data['donatee'],
            'amount' => $data['amount'],
            'donate_date' => $data['donate_date']
        ]);
    }
}
