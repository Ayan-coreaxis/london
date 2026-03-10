<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = ['title','slug','excerpt','body','image','category','author','status','sort_order'];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($m) => $m->slug = $m->slug ?: Str::slug($m->title));
    }

    public function scopePublished($query) { return $query->where('status','published'); }
    public function scopeOrdered($query)   { return $query->orderBy('sort_order')->orderBy('created_at','desc'); }
}
