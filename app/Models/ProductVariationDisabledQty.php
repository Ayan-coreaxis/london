<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariationDisabledQty extends Model {
    protected $table = 'product_variation_disabled_qty';
    protected $fillable = ['variation_id','quantity'];
}
