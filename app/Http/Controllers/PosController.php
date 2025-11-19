<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PosController extends Controller
{
    public function index()
    {
        return view('pos', [
            'customers' => Customer::orderBy('Name')->get(),
            'employees' => Employee::orderBy('Name')->get(),
            'products' => Product::orderBy('ProductName')->get(),
            'recentOrders' => Order::with(['customer', 'employee', 'orderDetails.product'])
                ->latest('OrderDate')
                ->limit(10)
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,CustomerID',
            'employee_id' => 'required|exists:employees,EmployeeID',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|distinct|exists:products,ProductID',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'CustomerID' => $data['customer_id'],
                'EmployeeID' => $data['employee_id'],
                'OrderDate' => now(),
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (! $product) {
                    throw ValidationException::withMessages([
                        'items' => ['One of the selected products no longer exists.'],
                    ]);
                }

                if ($product->Stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'items' => ["{$product->ProductName} only has {$product->Stock} left in stock."],
                    ]);
                }

                $product->decrement('Stock', $item['quantity']);

                OrderDetail::create([
                    'OrderID' => $order->OrderID,
                    'ProductID' => $product->ProductID,
                    'Quantity' => $item['quantity'],
                ]);
            }

            return $order->load(['customer', 'employee', 'orderDetails.product']);
        });

        return redirect()
            ->route('pos.index')
            ->with('status', "Order #{$order->OrderID} for {$order->customer->Name} created successfully.");
    }
}
