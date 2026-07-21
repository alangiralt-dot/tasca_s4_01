@extends('layouts.app')

@section('tab_name', 'Les meves comandes')

@section('content')
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-6">
    <div class="space-y-4">
        
        <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[15px] font-semibold text-gray-500 uppercase tracking-wider border-b border-[#bed1dc]">
            <div class="col-span-2">Codi</div>
            <div class="col-span-2">Estat</div>
            <div class="col-span-2">Data</div>
            <div class="col-span-2">Disponibilitat</div>
            <div class="col-span-2 text-right pr-2">Import</div>
            <div class="col-span-2 text-center">Accions</div>
        </div>

        <div class="divide-y divide-[#bed1dc] !mt-0">
           
            @foreach($confirmedOrders as $order)
            <a href="{{ route('orders.showOrderDetails', $order->id) }}" 
               class="py-3 grid grid-cols-12 items-center gap-4 hover:bg-gray-50 px-2 transition text-[13px] text-black font-normal">
                
                <div class="col-span-2 text-black font-normal tracking-wide whitespace-nowrap">
                    {{ $order->code }}
                </div>
                
                <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                    <span>{{ $order->status->status }}</span>
                </div>
                
                <div class="col-span-2 text-black tracking-wide uppercase whitespace-nowrap font-normal">
                    {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i') }}
                </div>
                
                <div class="col-span-2 flex items-center gap-2 text-black font-normal">
                    <span class="truncate">{{ $order->order_availability }}</span>
                </div>
                
                <div class="col-span-2 text-right pr-2 font-bold text-black">
                    <span>{{ number_format($order->total_amount, 2, ',', '.') }} €</span>
                </div>
                
                <div class="col-span-2 text-center text-[13px] text-blue-600 mt-1 font-normal tracking-wider cursor-pointer">
                    detalls
                </div>
            </a>
            @endforeach

        </div>
    </div>
</div>
@endsection
