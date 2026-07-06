@extends('layouts.app')

@section('tab_name', 'Detall de la comanda')

@section('content')
<div class="space-y-6">

    @if(!empty($conflicting_references))
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
            <div class="text-red-500 mt-0.5">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-medium text-red-800">Atenció: Referències no disponibles</h4>
                <p class="text-xs text-red-700 mt-1 font-normal">
                    No s'ha pogut processar correctament la tarifa o mida dels següents llistons: 
                    <span class="font-bold">{{ implode(', ', $conflicting_references) }}</span>. 
                    S'han retirat quirúrgicament de la sessió.
                </p>
            </div>
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden p-6 space-y-4">
        
        <div class="grid grid-cols-12 gap-4 px-2 pb-2 text-[14px] font-normal text-black uppercase tracking-wider border-b border-[#bed1dc]">
            <div class="col-span-6">Descripció</div>
            <div class="col-span-2 text-center">Quantitat</div>
            <div class="col-span-2">Preu</div>
            <div class="col-span-2 text-right">Subtotal</div>
        </div>

        <div class="divide-y divide-[#bed1dc] !mt-0">
            @forelse($products as $product)
                <div class="py-3 font-normal text-black text-[13px] transition hover:bg-gray-50 px-2 space-y-1">
                    
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-6 uppercase text-black font-normal break-words">
                            {{ $product->fatherProduct->name ?? 'Material Industrial' }}
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-4 items-center">
                        
                        <div class="col-span-2 text-xs text-black tracking-wide">
                            {{ $product->reference }}
                        </div>

                        <div class="col-span-2 text-right font-normal tracking-wide text-black whitespace-nowrap">
                            @if((int)$product->height === -1)
                                Ø {{ (int)$product->width }} × {{ (int)$product->length }}
                            @else
                                {{ (int)$product->width }} × {{ (int)$product->height }} × {{ (int)$product->length }}
                            @endif
                        </div>
                        <div class="col-span-2 text-right font-normal"></div>
                        <div class="col-span-2 text-center font-normal flex justify-center">
                                {{ $product->pivot->quantity }}
                        </div>

                        <div class="col-span-2 tracking-wide whitespace-nowrap">
                            {{ number_format($product->pivot->sale_unit_price, 2, ',', '.') }} {{ $product->unit->unit }}
                        </div>

                        <div class="col-span-2 text-right font-bold text-black tracking-wide">
                            {{ number_format($product->pivot->subtotal, 2, ',', '.') }} €
                        </div>

                    </div>

                </div>
            @empty
                <div class="py-12 text-center text-gray-400 font-normal">
                    No hi ha cap llistó de fusta carregat en aquesta comanda.
                </div>
            @endforelse
        </div>


    </div>
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden py-6 px-8 space-y-4">

        <div class="grid grid-cols-12 gap-4">
            <div class="col-span-4 space-y-2 text-[13px] font-normal">
                <div class="flex justify-between text-black">
                    <span class="uppercase">Codi</span>
                    <span class="font-normal text-black tracking-wide">{{ $code }}</span>
                </div>
                <div class="flex justify-between text-black">
                    <span class="uppercase">Estat</span>
                    <span class="font-normal text-black">{{ $status }}</span>
                </div>
                <div class="flex justify-between text-black">
                    <span class="uppercase">Data</span>
                    <span class="font-normal text-black tracking-wide">{{ $date }}</span>
                </div>
            </div>
            <div class="col-span-4 space-y-2 text-[13px] font-normal"></div>
            <div class="col-span-4 space-y-2 text-[13px] font-normal">
                <div class="flex justify-between text-black">
                    <span class="uppercase">Base Imposable</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($taxableBasis, 2, ',', '.') }} €</span>
                </div>
                <div class="flex justify-between text-black">
                    <span>IVA (21%)</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($tax, 2, ',', '.') }} €</span>
                </div>
                <div class="flex justify-between text-black">
                    <span>TOTAL</span>
                    <span class="font-bold text-black tracking-wide">{{ number_format($total, 2, ',', '.') }} €</span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
