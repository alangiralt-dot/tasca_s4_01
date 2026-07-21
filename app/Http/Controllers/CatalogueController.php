<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ChildProduct;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    /**
     * Filter and display child products grouped by father product based on category slug.
     */
    public function showChildProducts(string $slug)
    {
        $slugToIdMap = [
            'llistons-de-fusta'                 => 7,
            'fusta-exterior'                    => 8,
            'bigues-fusta-laminades-autoclau'   => 11,
            'llistons-fusta-autoclau-marro'     => 9,
            'llistons-fusta-autoclau-verd'      => 10,
            'travesses-fusta-jardi'             => 12,
            'llistons-tropicals'                => 16,
            'motllures-de-fusta-pi-gallec'      => 4,
            'pals-rodons-de-fusta-a-l-autoclau' => 13,
            'perfils-laminats-finestra'         => 14,
            'fusta-vella-i-fusta-envellida'     => 15,
        ];

        if (!array_key_exists($slug, $slugToIdMap)) {
            abort(404);
        }

        $categoryId = $slugToIdMap[$slug];

        $category = Category::findOrFail($categoryId);

        $productsRaw = ChildProduct::with(['fatherProduct', 'availability', 'unit'])
            ->whereHas('fatherProduct', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->get();

        $groupedProducts = $productsRaw->groupBy(function ($product) {
            return $product->fatherProduct->name;
        });

        return view('catalogue', compact('groupedProducts', 'category'));
    }
}
