<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogueController extends Controller
{
    /**
     * Filtra i mostra els productes fill utilitzant un diccionari.
     */
    public function showChildProducts($slug)
    {
        $slugToIdMap = [
            'llistons-de-fusta'                  => 7,
            'fusta-exterior'                     => 8,
            'bigues-fusta-laminades-autoclau'    => 11,
            'llistons-fusta-autoclau-marro'      => 9,
            'llistons-fusta-autoclau-verd'       => 10,
            'travesses-fusta-jardi'              => 12,
            'llistons-tropicals'                 => 16,
            'motllures-de-fusta-pi-gallec'       => 4,
            'pals-rodons-de-fusta-a-l-autoclau'  => 13,
            'perfils-laminats-finestra'          => 14,
            'fusta-vella-i-fusta-envellida'      => 15,
        ];

        if (!array_key_exists($slug, $slugToIdMap)) {
            abort(404);
        }

        $categoryId = $slugToIdMap[$slug];

        $category = DB::table('categories')->where('id', $categoryId)->first();

        /*
        SELECT cp.*, fp.name, fp.image_path, a.availability, u.unit
        FROM father_products AS fp
        INNER JOIN child_products AS cp ON fp.id = cp.father_product_id
        INNER JOIN availabilities AS a ON cp.availability_id = a.id
        INNER JOIN units AS u ON cp.unit_id = u.id
        WHERE fp.category_id = 11;
        */
        
        $productsRaw = DB::table('father_products as fp')
            ->join('child_products as cp', 'fp.id', '=', 'cp.father_product_id')
            ->join('availabilities as a', 'cp.availability_id', '=', 'a.id')
            ->join('units as u', 'cp.unit_id', '=', 'u.id')
            ->select('cp.*', 'fp.name as father_name', 'fp.image_path as father_image', 'a.availability as availability_text', 'u.unit as unit_text')
            ->where('fp.category_id', $categoryId)
            ->get();

        $groupedProducts = $productsRaw->groupBy('father_name');
        return view('catalogue', compact('groupedProducts', 'category'));
    }
}