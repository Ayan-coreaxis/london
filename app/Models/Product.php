<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Product extends Model {
    protected $fillable = ['name','slug','category','description','base_price','sku','image','status'];
    public function options()  { return $this->hasMany(ProductOption::class)->orderBy('sort_order'); }
    public function presets()  { return $this->hasMany(ProductPreset::class)->orderBy('sort_order'); }
    public function variants() { return $this->hasMany(ProductVariant::class)->orderBy('sort_order'); }
    public function faqs()     { return $this->hasMany(ProductFaq::class)->orderBy('sort_order'); }
    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->slug = $m->slug ?: Str::slug($m->name));
    }
}
