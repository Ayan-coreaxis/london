<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model {
    protected $fillable = ['product_id','name','visible','used_for_variations','sort_order'];
    protected $casts = ['visible'=>'boolean','used_for_variations'=>'boolean'];

    public function product() { return $this->belongsTo(Product::class); }
    public function values()  { return $this->hasMany(ProductAttributeValue::class, 'attribute_id')->orderBy('sort_order'); }
}
