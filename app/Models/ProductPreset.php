<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProductPreset extends Model {
    protected $fillable = ['product_id','label','description','badge_color','sort_order'];
}
