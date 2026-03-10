<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model {
    protected $fillable = ['product_id','sku','enabled','sort_order'];
    protected $casts = ['enabled'=>'boolean'];

    public function product()          { return $this->belongsTo(Product::class); }
    public function attributeValues()  { return $this->hasMany(ProductVariationAttribute::class, 'variation_id'); }
    public function pricing()          { return $this->hasMany(ProductVariationPricing::class, 'variation_id'); }
    public function disabledQuantities() { return $this->hasMany(ProductVariationDisabledQty::class, 'variation_id'); }
}
