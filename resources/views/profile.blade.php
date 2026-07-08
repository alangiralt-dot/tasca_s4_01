@extends('layouts.app')

@section('tab_name', 'El meu perfil')

@section('content')
<div class="bg-white rounded-2xl border border-[#e2e8f0] p-8 max-w-2xl mx-auto shadow-sm">
    
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="border-b border-gray-100 pb-4 mb-6">
        <h2 class="text-xl font-bold text-[#0f172a]">Modificar dades del perfil</h2>
        <p class="text-sm text-gray-500 mt-1">Actualitza la informació comercial de la fusteria per a les comandes.</p>
    </div>

    <form action="{{ url('/el-meu-perfil') }}" method="POST" class="space-y-5">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Nom</label>
                <input type="text" name="first_name" value="{{ old('first_name', $customer->first_name) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('first_name') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Cognoms</label>
                <input type="text" name="last_name" value="{{ old('last_name', $customer->last_name) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('last_name') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Telèfon de contacte</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('phone') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Adreça</label>
            <input type="text" name="street" value="{{ old('street', $customer->street) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('street') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            @error('street') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Número</label>
                <input type="text" name="address_number" value="{{ old('address_number', $customer->address_number) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('address_number') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('address_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Pis</label>
                <input type="text" name="address_floor" value="{{ old('address_floor', $customer->address_floor) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Porta</label>
                <input type="text" name="door" value="{{ old('door', $customer->door) }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Codi Postal</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $customer->postal_code) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('postal_code') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('postal_code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Ciutat</label>
                <input type="text" name="city_name" value="{{ old('city_name', $customer->city?->city) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('city_name') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('city_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Província</label>
                <input type="text" name="province_name" value="{{ old('province_name', $customer->city?->province?->province) }}" class="w-full px-4 py-2.5 bg-gray-50 border @error('province_name') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
                @error('province_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                Desar canvis
            </button>
        </div>
    </form>
</div>
@endsection
