<?php

namespace App\Models;

use Database\Factories\OpeningHourFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * A single weekday's opening window. `day_of_week` follows ISO-8601 —
 * 1 is Monday through to 7 for Sunday.
 *
 * @property int $id
 * @property int $day_of_week
 * @property string|null $opens_at
 * @property string|null $closes_at
 * @property bool $is_closed
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string|null $window
 */
#[Fillable(['day_of_week', 'opens_at', 'closes_at', 'is_closed'])]
class OpeningHour extends Model
{
    /** @use HasFactory<OpeningHourFactory> */
    use HasFactory;

    /**
     * @param  Builder<covariant static>  $query
     */
    public function scopeOrdered(Builder $query): void
    {
        $query->orderBy('day_of_week');
    }

    /**
     * The opening window as shown on the storefront, or null when closed.
     *
     * @return Attribute<string|null, never>
     */
    protected function window(): Attribute
    {
        return Attribute::make(get: function (): ?string {
            if ($this->is_closed || blank($this->opens_at) || blank($this->closes_at)) {
                return null;
            }

            return static::formatTime($this->opens_at).'–'.static::formatTime($this->closes_at);
        });
    }

    /**
     * Collapse consecutive weekdays that share the same window into single rows.
     *
     * @param  Collection<int, static>  $hours
     * @return list<array{from: int, to: int, window: string|null}>
     */
    public static function group(Collection $hours): array
    {
        $rows = [];
        $last = -1;

        foreach ($hours->sortBy('day_of_week') as $hour) {
            if ($last >= 0 && $rows[$last]['window'] === $hour->window && $rows[$last]['to'] === $hour->day_of_week - 1) {
                $rows[$last]['to'] = $hour->day_of_week;

                continue;
            }

            $rows[] = ['from' => $hour->day_of_week, 'to' => $hour->day_of_week, 'window' => $hour->window];
            $last++;
        }

        return $rows;
    }

    /**
     * Normalise a stored time to `H:i`, tolerating both `H:i` and `H:i:s`.
     */
    protected static function formatTime(string $time): string
    {
        return substr($time, 0, 5);
    }
}
