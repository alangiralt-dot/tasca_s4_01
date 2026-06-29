<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Mostra el formulari per a modificar el client de proves (U del CRUD).
     */
    public function edit()
    {
        /*
        SELECT cu.*, ci.city, p.province
        FROM customers AS cu
        INNER JOIN cities AS ci ON cu.city_id = ci.id
        INNER JOIN provinces AS p ON ci.province_id = p.id
        WHERE cu.id = 1
        LIMIT 1;
        */
        $customer = DB::table('customers')
            ->join('cities', 'customers.city_id', '=', 'cities.id')
            ->join('provinces', 'cities.province_id', '=', 'provinces.id')
            ->select('customers.*', 'cities.city as city_name', 'provinces.province as province_name')
            ->where('customers.id', 1)
            ->first();

        return view('profile', compact('customer'));
    }
    /**
     * Processa l'enviament del formulari i actualitza MariaDB (U del CRUD).
     */
    public function update(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'street'         => 'required|string|max:255',
            'address_number' => 'required|string|max:10',
            'address_floor'  => 'nullable|string|max:255',
            'door'           => 'nullable|string|max:255',
            'postal_code'    => 'required|string|max:10',
            'city_name'      => 'required|string|max:255',
            'province_name'  => 'required|string|max:255',
        ]);

        $cityNameClean = trim($request->input('city_name'));
        $provinceNameClean = trim($request->input('province_name'));

        $provinceId = DB::table('provinces')->where('province', $provinceNameClean)->value('id');

        if ($provinceId) {
            $cityExists = DB::table('cities')
                ->where('city', $cityNameClean)
                ->where('province_id', $provinceId)
                ->exists();

            if (!$cityExists) {
                DB::table('cities')->insert([
                    'city' => $cityNameClean,
                    'province_id' => $provinceId
                ]);
            }
        } else {
            DB::table('provinces')->insert([
                'province' => $provinceNameClean
            ]);

            $provinceId = DB::table('provinces')->where('province', $provinceNameClean)->value('id');

            DB::table('cities')->insert([
                'city' => $cityNameClean,
                'province_id' => $provinceId
            ]);
        }

        $finalCityId = DB::table('cities')
            ->where('city', $cityNameClean)
            ->where('province_id', $provinceId)
            ->value('id');

        DB::table('customers')->where('id', 1)->update([
            'first_name'     => $request->input('first_name'),
            'last_name'      => $request->input('last_name'),
            'phone'          => $request->input('phone'),
            'street'         => $request->input('street'),
            'address_number' => $request->input('address_number'),
            'address_floor'  => $request->input('address_floor'),
            'door'           => $request->input('door'),
            'postal_code'    => $request->input('postal_code'),
            'city_id'        => $finalCityId,
        ]);
        
        return redirect('/el-meu-perfil')->with('success', 'Perfil i localització actualitzats correctament!');
    }
}