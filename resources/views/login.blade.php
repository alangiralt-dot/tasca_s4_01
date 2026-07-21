@extends('layouts.app')

@section('tab_name', 'Login')

@section('content')
<div class="bg-white rounded-2xl border border-[#e2e8f0] p-8 max-w-2xl mx-auto shadow-sm">
    <form action="{{ url('/login') }}" method="POST" class="space-y-5">    
        @csrf
        
        <div class="grid grid-cols-12 gap-4">

            <div class="col-span-12">
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Correu electrònic</label>
                <input type="email" name="email" value="" required class="w-full px-4 py-2.5 bg-gray-50 border @error('email') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition">
            </div>

            <div class="col-span-12">
                <label class="pl-4 block text-[13px] font-semibold text-gray-500 uppercase tracking-wider mb-2">Contrasenya</label>
                <input type="password" name="password" value="" required class="w-full px-4 py-2.5 bg-gray-50 border @error('password') border-red-500 @else border-gray-200 @enderror rounded-xl text-sm focus:outline-none focus:border-[#bed1dc] transition">
            </div>
         </div>

        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-[#fffacd] hover:bg-[#fff27e] border border-[#bed1dc] px-4 py-2 rounded-xl text-xs text-black font-medium tracking-wider uppercase shadow-sm transition">
                Obrir Sessió
            </button>
        </div>
    </form>
</div>
@endsection
