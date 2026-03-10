<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductOption extends Model {
    protected $fillable = ['product_id','option_name','display_type','sort_order'];
    public function values() { return $this->hasMany(OptionValue::class,'option_id')->orderBy('sort_order'); }
}
