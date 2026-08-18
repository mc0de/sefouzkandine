<?php

namespace App\Models;

use App\Concerns\HasTranslatedAttributes;
use Database\Factories\MenuCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $slug
 * @property string $name_lt
 * @property string $name_en
 * @property string|null $description_lt
 * @property string|null $description_en
 * @property string $layout
 * @property int $position
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, MenuItem> $items
 * @property-read string $name
 * @property-read string|null $description
 */
#[Fillable(['slug', 'name_lt', 'name_en', 'description_lt', 'description_en', 'layout', 'position', 'is_active'])]
class MenuCategory extends Model
{
    /** @use HasFactory<MenuCategoryFactory> */
    use HasFactory, HasTranslatedAttributes;

    /**
     * The layouts a category may be rendered with on the storefront.
     */
    public const LAYOUTS = ['cards', 'list'];

    /**
     * @return HasMany<MenuItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('position')->orderBy('id');
    }

    /**
     * Only categories that should appear on the storefront.
     *
     * @param  Builder<covariant static>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Categories in the order they are shown on the storefront.
     *
     * @param  Builder<covariant static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('position')->orderBy('id');
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
