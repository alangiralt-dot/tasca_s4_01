<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Province;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    /**
     * Processa el registre públic d'un nou fuster (C de Create).
     */
    /**
     * Processa el registre públic d'un nou fuster (C de Create).
     */
    public function store(Request $request)
    {
        // 1. VALIDACIÓ DINÀMICA: Reben els textos de la ciutat i província, fidel al teu update
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'street' => ['required', 'string', 'max:255'],
            'address_number' => ['required', 'string', 'max:255'],
            'address_floor' => ['nullable', 'string', 'max:255'],
            'door' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:255'],
            'city_name' => ['required', 'string', 'max:255'],     // Text del formulari
            'province_name' => ['required', 'string', 'max:255'], // Text del formulari
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        // 2. NETEJA I CREACIÓ DINÀMICA (La teva brillant estructura clonada)
        $cityNameClean = trim($validated['city_name']);
        $provinceNameClean = trim($validated['province_name']);

        // Busquem o creem la província
        $province = Province::firstOrCreate(['province' => $provinceNameClean]);

        // Busquem o creem la ciutat lligada a la província
        $city = City::firstOrCreate([
            'city' => $cityNameClean,
            'province_id' => $province->id
        ]);

        // 3. EXECUTEM LA INSERCIÓ EN BLOC SEGUR
        $user = DB::transaction(function () use ($validated, $city) {
            // A. Creem el customer utilitzant la ID que acabem d'obtenir
            $customer = Customer::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'street' => $validated['street'],
                'address_number' => $validated['address_number'],
                'address_floor' => $validated['address_floor'] ?? null,
                'door' => $validated['door'] ?? null,
                'city_id' => $city->id, // Lligat perfectament
                'postal_code' => $validated['postal_code'],
            ]);

            // B. Creem el registre d'usuari
            return User::create([
                'name' => null, 
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'customer_id' => $customer->id,
            ]);
        });

        // 4. Sessió oberta i cap al carretó a comprar
        Auth::login($user);

        return redirect()->route('orders.showOrderDetails.current');
    }
    
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

        $customerId = \Illuminate\Support\Facades\Auth::user()->customer_id;
        $customer = Customer::findOrFail($customerId);
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
