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
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Nom</label>
                <input type="text" name="first_name" value="{{ $customer->first_name }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Cognoms</label>
                <input type="text" name="last_name" value="{{ $customer->last_name }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Telèfon de contacte</label>
            <input type="text" name="phone" value="{{ $customer->phone }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Adreça</label>
            <input type="text" name="street" value="{{ $customer->street }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Número</label>
                <input type="text" name="address_number" value="{{ $customer->address_number }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Pis</label>
                <input type="text" name="address_floor" value="{{ $customer->address_floor }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Porta</label>
                <input type="text" name="door" value="{{ $customer->door }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Codi Postal</label>
                <input type="text" name="postal_code" value="{{ $customer->postal_code }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ciutat</label>
                <input type="text" name="city_name" value="{{ $customer->city_name }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Província</label>
                <input type="text" name="province_name" value="{{ $customer->province_name }}" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-600 transition">
            </div>
        </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#0f172a] hover:bg-black text-white px-6 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                Desar canvis
            </button>
        </div>
    </form>
</div>
@endsection
