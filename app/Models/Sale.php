<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded=[];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'transactions');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->with('product');
    }
    public function seller(){
        return $this->belongsTo(Seller::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
