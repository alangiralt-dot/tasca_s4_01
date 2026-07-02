<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Add a child product variant and its quantity to the current session order.
     */
    public function addToCurrentOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $productId = (int) $request->input('product_id');
        $quantity  = (int) $request->input('quantity');

        $currentOrder = $request->session()->get('current_order', []);

        if (array_key_exists($productId, $currentOrder)) {
            $currentOrder[$productId] += $quantity;
        } else {
            $currentOrder[$productId] = $quantity;
        }

        $request->session()->put('current_order', $currentOrder);

        return response()->json([
            'status'  => 'success',
            'message' => 'Product successfully added to the provisional order.'
        ], 200);
    }
}
