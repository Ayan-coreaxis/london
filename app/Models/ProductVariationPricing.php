<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariationPricing extends Model {
    protected $table = 'product_variation_pricing';
    protected $fillable = ['variation_id','turnaround_id','quantity','price','disabled'];
    protected $casts = ['price'=>'float','disabled'=>'boolean'];
}
