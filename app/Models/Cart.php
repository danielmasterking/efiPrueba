<?php

// app/Models/Cart.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['producto','stock','precio_total','user_id'];
    protected $table = 'cart';
    public function product()
    {
        return $this->belongsTo(Product::class, 'producto');
    }
}
