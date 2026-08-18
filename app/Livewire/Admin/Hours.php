<?php

namespace App\Livewire\Admin;

use App\Models\OpeningHour;
use Flux\Flux;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Edits the seven weekday opening windows shown on the storefront.
 */
#[Title('Opening hours')]
class Hours extends Component
{
    /**
     * The weekday rows being edited, keyed by their zero-based index.
     *
     * @var array<int, array{day_of_week: int, is_closed: bool, opens_at: string, closes_at: string}>
     */
    public array $days = [];

    /**
     * Load (and if necessary seed) the seven weekday rows.
     */
    public function mount(): void
    {
        $this->days = [];

        foreach (range(1, 7) as $dayOfWeek) {
            $hour = OpeningHour::query()->firstOrCreate(
                ['day_of_week' => $dayOfWeek],
                ['opens_at' => '12:00', 'closes_at' => '20:00', 'is_closed' => false],
            );

            $this->days[] = [
                'day_of_week' => (int) $hour->day_of_week,
                'is_closed' => (bool) $hour->is_closed,
                'opens_at' => static::toTimeInput($hour->opens_at),
                'closes_at' => static::toTimeInput($hour->closes_at),
            ];
        }
    }

    /**
     * Persist every weekday row at once.
     */
    public function save(): void
    {
        $this->validate($this->hourRules(), attributes: $this->hourValidationAttributes());

        foreach ($this->days as $day) {
            $isClosed = $day['is_closed'];

            OpeningHour::query()->updateOrCreate(
                ['day_of_week' => $day['day_of_week']],
                [
                    'is_closed' => $isClosed,
                    'opens_at' => $isClosed ? null : $day['opens_at'],
                    'closes_at' => $isClosed ? null : $day['closes_at'],
                ],
            );
        }

        Flux::toast(variant: 'success', text: __('Opening hours updated.'));
    }

    /**
     * The English weekday label for the given ISO weekday number.
     */
    public function dayLabel(int $dayOfWeek): string
    {
        return [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday'),
        ][$dayOfWeek] ?? (string) $dayOfWeek;
    }

    /**
     * Build the per-row validation rules.
     *
     * @return array<string, mixed>
     */
    protected function hourRules(): array
    {
        $rules = [
            'days' => ['array', 'size:7'],
        ];

        foreach ($this->days as $index => $day) {
            $isClosed = $day['is_closed'];

            $rules["days.{$index}.day_of_week"] = ['required', 'integer', 'between:1,7'];
            $rules["days.{$index}.is_closed"] = ['boolean'];
            $rules["days.{$index}.opens_at"] = $isClosed
                ? ['nullable']
                : ['required', 'date_format:H:i'];
            $rules["days.{$index}.closes_at"] = $isClosed
                ? ['nullable']
                : ['required', 'date_format:H:i', "after:days.{$index}.opens_at"];
        }

        return $rules;
    }

    /**
     * Friendly attribute names so the validation messages name the weekday.
     *
     * @return array<string, string>
     */
    protected function hourValidationAttributes(): array
    {
        $attributes = [];

        foreach ($this->days as $index => $day) {
            $label = $this->dayLabel($day['day_of_week']);

            $attributes["days.{$index}.opens_at"] = mb_strtolower($label).' '.__('opening time');
            $attributes["days.{$index}.closes_at"] = mb_strtolower($label).' '.__('closing time');
        }

        return $attributes;
    }

    /**
     * Normalise a stored `H:i:s` time down to the `H:i` a time input expects.
     */
    protected static function toTimeInput(?string $time): string
    {
        return blank($time) ? '' : mb_substr($time, 0, 5);
    }
}
