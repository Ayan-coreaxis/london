<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductVariationAttribute extends Model {
    protected $fillable = ['variation_id','attribute_id','attribute_value_id'];

    public function variation()      { return $this->belongsTo(ProductVariation::class, 'variation_id'); }
    public function attribute()      { return $this->belongsTo(ProductAttribute::class, 'attribute_id'); }
    public function attributeValue() { return $this->belongsTo(ProductAttributeValue::class, 'attribute_value_id'); }
}
