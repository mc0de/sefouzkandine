<?php

namespace App\Models;

use App\Concerns\HasTranslatedAttributes;
use Database\Factories\MenuItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $menu_category_id
 * @property string $name_lt
 * @property string $name_en
 * @property string|null $description_lt
 * @property string|null $description_en
 * @property string $price
 * @property string|null $art
 * @property string|null $tag
 * @property int $position
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read MenuCategory $category
 * @property-read string $name
 * @property-read string|null $description
 * @property-read string $formatted_price
 */
#[Fillable([
    'menu_category_id', 'name_lt', 'name_en', 'description_lt', 'description_en',
    'price', 'art', 'tag', 'position', 'is_active',
])]
class MenuItem extends Model
{
    /** @use HasFactory<MenuItemFactory> */
    use HasFactory, HasTranslatedAttributes;

    /**
     * The illustration components available in resources/views/components/site/art.
     */
    public const ARTWORK = [
        'wings', 'wings-bbq', 'wings-teriyaki', 'wings-chef', 'wings-mango',
        'fries', 'fries-tex-mex', 'strips', 'onion-rings', 'cup',
    ];

    /**
     * Highlight badges a menu item may carry. Rendered via the `site.tags.*` translations.
     */
    public const TAGS = ['hit', 'new', 'spicy', 'vege'];

    /**
     * @return BelongsTo<MenuCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * Only items that should appear on the storefront.
     *
     * @param  Builder<covariant static>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @return Attribute<string, never>
     */
    protected function name(): Attribute
    {
        return Attribute::make(get: fn (): string => (string) $this->translate('name'));
    }

    /**
     * @return Attribute<string|null, never>
     */
    protected function description(): Attribute
    {
        return Attribute::make(get: fn (): ?string => $this->translate('description'));
    }

    /**
     * Price rendered with the decimal separator the active locale expects.
     *
     * @return Attribute<string, never>
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(get: function (): string {
            $decimals = fmod((float) $this->price, 1.0) === 0.0 ? 0 : 2;

            return number_format(
                (float) $this->price,
                $decimals,
                app()->getLocale() === 'en' ? '.' : ',',
                '',
            );
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
