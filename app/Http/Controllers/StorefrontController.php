<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use App\Models\OpeningHour;
use Illuminate\Contracts\View\View;

class StorefrontController extends Controller
{
    /**
     * Render the public storefront for the active locale.
     */
    public function __invoke(): View
    {
        return view('welcome', [
            'categories' => MenuCategory::query()
                ->active()
                ->ordered()
                ->with(['items' => fn ($query) => $query->active()])
                ->get(),
            'openingHours' => OpeningHour::group(OpeningHour::ordered()->get()),
        ]);
    }
}
