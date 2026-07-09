<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Province;
use App\Models\City;

class ProfileController extends Controller
{
    /**
     * Mostra el formulari per a modificar el client de proves
     */
    public function edit()
    {
        // MODE PRIVAT: Si l'usuari ha iniciat sessió de veritat
        if (\Illuminate\Support\Facades\Auth::check()) {
            $customerId = \Illuminate\Support\Facades\Auth::user()->customer_id;
            $customer = Customer::with('city.province')->findOrFail($customerId);

            return view('profile', [
                'customer' => $customer,
                'isPublicMode' => false // Informem que som a la zona privada (Edició)
            ]);
        }

        // MODE PÚBLIC: Si el fuster és un convidat anònim (Alta)
        $customer = new Customer();
        
        return view('profile', [
            'customer' => $customer,
            'isPublicMode' => true // Informem que som a la zona pública (Registre)
        ]);
    }


    /**
     * Processa les dades del formulari i les actualitza a la base de dades
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

        $province = Province::firstOrCreate(['province' => $provinceNameClean]);

        $city = City::firstOrCreate([
            'city'        => $cityNameClean,
            'province_id' => $province->id
        ]);

        $customer = Customer::findOrFail(1);
        $customer->update([
            'first_name'     => $request->input('first_name'),
            'last_name'      => $request->input('last_name'),
            'phone'          => $request->input('phone'),
            'street'         => $request->input('street'),
            'address_number' => $request->input('address_number'),
            'address_floor'  => $request->input('address_floor'),
            'door'           => $request->input('door'),
            'postal_code'    => $request->input('postal_code'),
            'city_id'        => $city->id, // Assignem la ID del model obtingut
        ]);
        /*
        SELECT cu.*, ci.city, p.province
        FROM customers AS cu
        INNER JOIN cities AS ci ON cu.city_id = ci.id
        INNER JOIN provinces AS p ON ci.province_id = p.id
        WHERE cu.id = 1
        LIMIT 1;
        */        
        return redirect('/el-meu-perfil')->with('success', "El teu perfil s'ha actualitzat correctament");
    }
}
