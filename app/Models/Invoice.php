<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'price', 'date', 'status'];

    
    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('price');
    }
}
