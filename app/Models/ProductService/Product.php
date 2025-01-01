<?php

namespace App\Models\ProductService;

use App\Models\MediaStorage\MediaStorage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory;
    //use HasFactory, Searchable;
    protected $keyType = 'string';
    public $incrementing = false;

//    /**
//     *  Get array, when will use for index at Typesense
//     *
//     *  @return array
//     */
//    public function toSearchableArray()
//    {
//        // Index fields
//        return [
//            'title' => $this->title,
//            'description' => $this->description,
//        ];
//    }
//
//    /**
//     *  Get Typesense configuration
//     *
//     *  @return array
//     */
//    public function typesenseIndexSettings(): array
//    {
//        return [
//            'fields' => [
//                ['name' => 'title', 'type' => 'string'],
//                ['name' => 'description', 'type' => 'string'],
//            ],
//            'default_sorting_field' => 'title',
//        ];
//    }

    protected $fillable = [
        'id',
        'title',
        'description',
        'price',
        'old_price',
        'response_id',
        'platform_id',
        'category_id',
        'delivery_id',
        'delivery_id',
        'created_at',
    ];

    protected $hidden = [
        'response_id',
        'platform_id',
        'category_id',
        'delivery_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function casts()
    {
        return [
            'id' => 'string',
            'price' => 'float',
            'old_price' => 'float',
            'response_id' => 'string',
            'platform_id' => 'string',
            'category_id' => 'string',
            'delivery_id' => 'string',
        ];
    }

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'category_id');
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'delivery_id');
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'delivery_id');
    }

    public function images(): BelongsToMany
    {
        return $this->belongsToMany(MediaStorage::class, 'product_pictures', 'product_id', 'image_id');
    }

    public function seller(): belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
