<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sound &amp; Brews POS</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600" rel="stylesheet" />
    <style>
        :root {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #111827;
            background: #f3f4f6;
        }
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, #fdf2f8, #eef2ff);
        }
        .page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
        .grid {
            display: grid;
            gap: 24px;
        }
        @media (min-width: 1024px) {
            .grid {
                grid-template-columns: 2fr 1fr;
            }
        }
        .card {
            background: #fff;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 20px 40px -24px rgba(15, 23, 42, 0.4);
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475467;
            margin-bottom: 4px;
        }
        select {
            width: 100%;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid #d0d5dd;
            font-size: 1rem;
        }
        .products {
            display: grid;
            gap: 12px;
        }
        @media (min-width: 640px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .products {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .product {
            border: 1px solid #e4e7ec;
            border-radius: 16px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            background: #fff;
        }
        .product h3 {
            margin: 0;
            font-size: 1rem;
        }
        .product small {
            color: #667085;
        }
        .pill {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            border-radius: 999px;
            padding: 2px 10px;
            background: #f4f7ff;
            color: #1d4ed8;
        }
        button {
            cursor: pointer;
        }
        .btn {
            border: none;
            border-radius: 12px;
            padding: 10px 14px;
            font-weight: 600;
            transition: transform 0.1s ease, box-shadow 0.1s ease;
        }
        .btn:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px -12px rgba(15, 23, 42, 0.4);
        }
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none;
        }
        .btn-primary {
            background: #111827;
            color: #fff;
            width: 100%;
        }
        .btn-secondary {
            background: #f3f4f6;
            color: #111827;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 0;
            font-size: 0.9rem;
        }
        th {
            text-align: left;
            color: #475467;
        }
        td:last-child, th:last-child {
            text-align: right;
        }
        .cart-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 12px;
        }
        .status {
            padding: 12px 16px;
            border-radius: 12px;
            background: #ecfdf3;
            color: #027a48;
            margin-bottom: 16px;
        }
        .error {
            background: #fef3f2;
            color: #b42318;
        }
        .history {
            margin-top: 24px;
        }
        .history h2 {
            margin: 0 0 12px;
            font-size: 1.2rem;
        }
        .history table {
            border-top: 1px solid #e4e7ec;
        }
        .qty-btn {
            border: 1px solid #d0d5dd;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        .qty-value {
            min-width: 32px;
            text-align: center;
            display: inline-block;
        }
        .cart-empty {
            text-align: center;
            padding: 16px 0;
            color: #98a2b3;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
    <div class="page">
        <header>
            <p class="pill">Sound &amp; Brews POS</p>
            <h1>Point of Sale</h1>
            <p style="color:#475467;margin-top:-8px">Create coffee orders, see inventory, and track the latest sales.</p>
        </header>

        @if (session('status'))
            <div class="status">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="status error">
                <ul style="margin:0;padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('pos.store') }}" id="posForm" class="grid">
            @csrf
            <div class="card" style="display:flex;flex-direction:column;gap:24px;">
                <div style="display:grid;gap:12px;">
                    <div>
                        <label for="customer">Customer</label>
                        <select name="customer_id" id="customer" required>
                            <option value="" disabled selected>Select customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->CustomerID }}">{{ $customer->Name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="employee">Served by</label>
                        <select name="employee_id" id="employee" required>
                            <option value="" disabled selected>Select employee</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->EmployeeID }}">{{ $employee->Name }} ({{ $employee->Role }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <h2 style="margin-bottom:12px;">Menu</h2>
                    <div class="products">
                        @foreach ($products as $product)
                            <div class="product">
                                <h3>{{ $product->ProductName }}</h3>
                                <small>₱{{ number_format($product->Price, 2) }}</small>
                                <span class="pill">{{ $product->Stock }} in stock</span>
                                <button
                                    type="button"
                                    class="btn btn-secondary add-to-cart"
                                    data-product='{{ json_encode([
                                        "id" => $product->ProductID,
                                        "name" => $product->ProductName,
                                        "price" => $product->Price,
                                        "stock" => $product->Stock
                                    ]) }}'

                                >
                                    Add to cart
                                </button>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card" style="display:flex;flex-direction:column;gap:16px;">
                <div>
                    <h2 style="margin:0;">Cart</h2>
                    <p style="color:#98a2b3;margin:4px 0 0;">Tap menu items to build the order.</p>
                </div>
                <div class="cart" data-cart>
                    <table>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr class="cart-empty">
                                <td colspan="3">No items yet</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart-total">
                        <span>Order total</span>
                        <span>₱<span id="cartTotal">0.00</span></span>
                    </div>
                </div>
                <div id="cartHiddenInputs"></div>
                <button class="btn btn-primary" id="checkoutBtn" type="submit" disabled>Complete Order</button>
            </div>
        </form>

        <section class="history card">
            <h2>Recent orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Handled by</th>
                        <th>Total Items</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        @php
                            $itemCount = $order->orderDetails->sum('Quantity');
                            $orderTotal = $order->orderDetails->reduce(function ($carry, $detail) {
                                return $carry + ($detail->Quantity * $detail->product->Price);
                            }, 0);
                        @endphp
                        <tr>
                            <td>#{{ $order->OrderID }}</td>
                            <td>{{ $order->customer->Name ?? 'Walk-in' }}</td>
                            <td>{{ $order->employee->Name ?? 'N/A' }}</td>
                            <td>{{ $itemCount }}</td>
                            <td>₱{{ number_format($orderTotal, 2) }}</td>
                            <td>{{ $order->OrderDate->format('M d, Y g:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="color:#98a2b3;text-align:center;padding:24px 0;">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <script>
        const cart = new Map();
        const cartBody = document.getElementById('cartBody');
        const cartTotal = document.getElementById('cartTotal');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const hiddenInputs = document.getElementById('cartHiddenInputs');

        const formatMoney = (value) => Number(value).toFixed(2);

        const renderCart = () => {
            cartBody.innerHTML = '';

            if (cart.size === 0) {
                cartBody.innerHTML = '<tr class="cart-empty"><td colspan="3">No items yet</td></tr>';
                cartTotal.textContent = '0.00';
                checkoutBtn.disabled = true;
                hiddenInputs.innerHTML = '';
                return;
            }

            let index = 0;
            let total = 0;
            hiddenInputs.innerHTML = '';

            cart.forEach((item) => {
                const lineTotal = item.price * item.quantity;
                total += lineTotal;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <strong>${item.name}</strong>
                        <div style="color:#98a2b3;font-size:0.85rem;">₱${formatMoney(item.price)}</div>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="qty-btn" data-action="decrease" data-id="${item.id}">-</button>
                        <span class="qty-value">${item.quantity}</span>
                        <button type="button" class="qty-btn" data-action="increase" data-id="${item.id}">+</button>
                    </td>
                    <td>₱${formatMoney(lineTotal)}</td>
                `;
                cartBody.appendChild(row);

                hiddenInputs.insertAdjacentHTML('beforeend', `
                    <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                `);

                index++;
            });

            cartTotal.textContent = formatMoney(total);
            checkoutBtn.disabled = false;
        };

        document.querySelectorAll('.add-to-cart').forEach((button) => {
            button.addEventListener('click', () => {
                const product = JSON.parse(button.dataset.product);
                const existing = cart.get(product.id) || { ...product, quantity: 0 };

                if (existing.quantity >= product.stock) {
                    alert(`${product.name} is out of stock.`);
                    return;
                }

                existing.quantity += 1;
                cart.set(product.id, existing);
                renderCart();
            });
        });

        cartBody.addEventListener('click', (event) => {
            const btn = event.target.closest('.qty-btn');
            if (!btn) return;

            const id = Number(btn.dataset.id);
            const item = cart.get(id);
            if (!item) return;

            if (btn.dataset.action === 'increase') {
                if (item.quantity >= item.stock) {
                    alert(`${item.name} is out of stock.`);
                    return;
                }
                item.quantity += 1;
            } else {
                item.quantity -= 1;
                if (item.quantity <= 0) {
                    cart.delete(id);
                } else {
                    cart.set(id, item);
                }
            }

            renderCart();
        });
    </script>
</body>
</html>
