<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OptionValue extends Model {
    protected $fillable = ['option_id','value_label','extra_price','sort_order'];
    protected $casts = ['extra_price'=>'float'];
}
