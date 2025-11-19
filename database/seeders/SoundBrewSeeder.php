<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class SoundBrewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        OrderDetail::truncate();
        Order::truncate();
        Customer::truncate();
        Employee::truncate();
        Product::truncate();

        Schema::enableForeignKeyConstraints();

        $timestamp = Carbon::now();

        $customers = [
            ['Name' => 'Alice Reyes', 'Email' => 'alice@example.com', 'Phone' => '09171234567'],
            ['Name' => 'Juan Dela Cruz', 'Email' => 'juan@example.com', 'Phone' => '09179876543'],
            ['Name' => 'Maria Santos', 'Email' => 'maria@example.com', 'Phone' => '09173456789'],
            ['Name' => 'Kevin Tan', 'Email' => 'kevin@example.com', 'Phone' => '09221234567'],
            ['Name' => 'Lara Lim', 'Email' => 'lara@example.com', 'Phone' => '09334567890'],
        ];

        foreach ($customers as &$customer) {
            $customer['created_at'] = $timestamp;
            $customer['updated_at'] = $timestamp;
        }
        unset($customer);
        Customer::insert($customers);

        $products = [
            ['ProductName' => 'Cappuccino', 'Price' => 120.00, 'Stock' => 50],
            ['ProductName' => 'Latte', 'Price' => 130.00, 'Stock' => 40],
            ['ProductName' => 'Espresso', 'Price' => 100.00, 'Stock' => 60],
            ['ProductName' => 'Mocha', 'Price' => 140.00, 'Stock' => 35],
            ['ProductName' => 'Americano', 'Price' => 110.00, 'Stock' => 50],
            ['ProductName' => 'Iced Coffee', 'Price' => 125.00, 'Stock' => 45],
            ['ProductName' => 'Blueberry Muffin', 'Price' => 80.00, 'Stock' => 30],
            ['ProductName' => 'Chocolate Cake', 'Price' => 150.00, 'Stock' => 25],
        ];

        foreach ($products as &$product) {
            $product['created_at'] = $timestamp;
            $product['updated_at'] = $timestamp;
        }
        unset($product);
        Product::insert($products);

        $employees = [
            ['Name' => 'John Cruz', 'Role' => 'Admin', 'Email' => 'john.admin@example.com', 'Password' => bcrypt('password123')],
            ['Name' => 'Anna Lim', 'Role' => 'Cashier', 'Email' => 'anna.cashier@example.com', 'Password' => bcrypt('password123')],
        ];

        foreach ($employees as &$employee) {
            $employee['created_at'] = $timestamp;
            $employee['updated_at'] = $timestamp;
        }
        unset($employee);
        Employee::insert($employees);

        $customerLookup = Customer::pluck('CustomerID', 'Name');
        $employeeLookup = Employee::pluck('EmployeeID', 'Name');
        $productLookup = Product::pluck('ProductID', 'ProductName');

        $orders = [
            ['name' => 'Alice Reyes', 'employee' => 'Anna Lim', 'date' => '2025-11-01 09:30:00'],
            ['name' => 'Juan Dela Cruz', 'employee' => 'Anna Lim', 'date' => '2025-11-01 10:15:00'],
            ['name' => 'Maria Santos', 'employee' => 'John Cruz', 'date' => '2025-11-02 11:00:00'],
            ['name' => 'Kevin Tan', 'employee' => 'Anna Lim', 'date' => '2025-11-03 14:45:00'],
            ['name' => 'Lara Lim', 'employee' => 'John Cruz', 'date' => '2025-11-03 15:30:00'],
        ];

        $orderDetails = [
            [1, 'Cappuccino', 2],
            [1, 'Blueberry Muffin', 1],
            [2, 'Latte', 1],
            [2, 'Chocolate Cake', 1],
            [3, 'Espresso', 3],
            [4, 'Americano', 2],
            [4, 'Blueberry Muffin', 2],
            [5, 'Mocha', 1],
            [5, 'Chocolate Cake', 1],
        ];

        foreach ($orders as $index => $orderData) {
            $order = Order::create([
                'CustomerID' => $customerLookup[$orderData['name']],
                'EmployeeID' => $employeeLookup[$orderData['employee']],
                'OrderDate' => Carbon::parse($orderData['date']),
            ]);

            foreach ($orderDetails as $detail) {
                if ($detail[0] === $index + 1) {
                    OrderDetail::create([
                        'OrderID' => $order->OrderID,
                        'ProductID' => $productLookup[$detail[1]],
                        'Quantity' => $detail[2],
                    ]);
                }
            }
        }
    }
}
