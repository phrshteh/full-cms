<?php

namespace Phrshte\FullCms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $with = [
        'parent'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'description',
        'thumbnail_width',
        'thumbnail_height',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function extraFields(): HasMany
    {
        return $this->hasMany(ExtraField::class, 'category_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'category_id');
    }
}
