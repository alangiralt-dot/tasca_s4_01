<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ChildProduct;

class OrderController extends Controller
{
    /**
     * Display the general list of orders
     */
    public function showOrders(Request $request)
    {
        $confirmedOrders = Order::with('status') 
            ->where('customer_id', 1)
            ->orderBy('date', 'desc')
            ->get();

        return view('orders', [
            'confirmedOrders' => $confirmedOrders
        ]);
    }

    /**
     * Display the detailed items of a specific order (Wireframe 2)
     */
    public function showOrderDetails(Request $request, $id)
    {
        $taxableBasis = 0.00;
        $tax = 0.00;
        $total = 0.00;
        $conflicting_references = [];

        $order = Order::with(['childProducts.fatherProduct', 'childProducts.unit', 'childProducts.availability', 'status'])->findOrFail($id);

        $code = $order->code;
        $status = $order->status->status;
        $date = \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i');

        $taxableBasis = round($order->childProducts->sum('pivot.subtotal'), 2);
        
        $tax = round($taxableBasis * 0.21, 2);
        
        $total = $order->total_amount;

        return view('invoice', [
            'order'                  => $order,
            'products'               => $order->childProducts,
            'isCurrent'          => false,
            'code'                   => $code,
            'status'                 => $status,
            'date'                   => $date,
            'taxableBasis'           => $taxableBasis,
            'tax'                    => $tax,
            'total'                  => $total,
            'conflicting_references' => $conflicting_references
        ]);

    }
}