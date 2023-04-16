<!DOCTYPE html>
<html>
<head>
    <title>Foodics Test</title>
    <style type="text/css">
        div {
            border: 10px dashed rgba(0, 0, 0, 0.03);
            margin: 1rem;
        }
        i {
            font-family: monospace;
            margin: 0 1rem;
            color: darkblue;
            font-size: 16px;
            font-weight: 100;
        }
        li {
            font-size: 14px;
            color: #aaa;
            padding: 0.5rem 0;
        }
        li[onClick] {
            cursor: pointer;
        }
        li[onClick]:hover {
            background-color: rgba(0, 0, 255, 0.1);
        }
        label {
            cursor: pointer;
            border: 10px dashed rgba(0, 0, 255, 0.5);
            padding: 1rem 0;
            display: inline-block;
        }
    </style>
</head>
<body>
    <h1>Foodics Test</h1>

    @if (env('LARAVEL_SAIL'))
        <a href="http://localhost:8025">Mailpit Dashboard</a>
    @endif


    <section>
        <div>
            <h2>Orders</h2>
            <button onClick="createOrder()">Create Order</button>
            <ul>
                @foreach($orders as $order)
                    <li>ID: <i>{{ $order->id }}</i> Stock ID: <i>{{ $order->stock_id }}</i> User ID: <i>{{ $order->user_id }}</i> Created: <i>{{ $order->created_at }}</i> Updated: <i>{{ $order->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2>Ingredients</h2>
            <ul>
                @foreach($ingredients as $ingredient)
                    <li onClick="addIngredient('{{ $ingredient->id }}')">
                        ID: <i>{{ $ingredient->id }}</i> Stock ID: <i>{{ $ingredient->stock_id }}</i> Name: <i>{{ $ingredient->name }}</i> Recommended Quantity: <i>{{ $ingredient->recommended_quantity }}</i> Quantity: <label><i>{{ $ingredient->quantity }}</i></label> Created: <i>{{ $ingredient->created_at }}</i> Updated: <i>{{ $ingredient->updated_at }}</i>
                    </li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h2>Stocks</h2>
            <ul>
                @foreach($stocks as $stock)
                    <li>ID: <i>{{ $stock->id }}</i> Created: <i>{{ $stock->created_at }}</i> Updated: <i>{{ $stock->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h2>Products</h2>
            <ul>
                @foreach($products as $product)
                    <li>ID: <i>{{ $product->id }}</i> Name: <i>{{ $product->name }}</i> Created: <i>{{ $product->created_at }}</i> Updated: <i>{{ $product->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h2>Product-Stock Pivot Table</h2>
            <ul>
                @foreach($productStock as $ps)
                    <li>ID: <i>{{ $ps->id }}</i> Product ID: <i>{{ $ps->product_id }}</i> Stock ID: <i>{{ $ps->stock_id }}</i> Created: <i>{{ $ps->created_at }}</i> Updated: <i>{{ $ps->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h2>Ingredient-Product Pivot Table</h2>
            <ul>
                @foreach($ingredientProduct as $ip)
                    <li>ID: <i>{{ $ip->id }}</i> Product ID: <i>{{ $ip->product_id }}</i> Ingredient ID: <i>{{ $ip->ingredient_id }}</i> Quantity: <i>{{ $ip->quantity }}</i> Created: <i>{{ $ip->created_at }}</i> Updated: <i>{{ $ip->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>
        
        <div>
            <h2>Order-Product Pivot Table</h2>
            <ul>
                @foreach($orderProduct as $op)
                    <li>ID: <i>{{ $op->id }}</i> Order ID: <i>{{ $op->order_id }}</i> Product ID: <i>{{ $op->product_id }}</i> Quantity: <i>{{ $op->quantity }}</i> Created: <i>{{ $op->created_at }}</i> Updated: <i>{{ $op->updated_at }}</i></li>
                @endforeach
            </ul>
        </div>
    </section>

    <script type="text/javascript">
        function addIngredient(id) {
            const quantity = prompt('Enter quantity to add', '100');
            const data = {
                'quantity': quantity,
            };

            fetch(`/api/ingredients/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    response.json().then(data => {
                        location.reload();
                    });
                })
        }

        function createOrder() {
            const quantity = prompt('Enter quantity', '1');
            const data = {
                'products': [
                    {
                        'id': 1,
                        'quantity': quantity,
                    }
                ]
            };

            fetch('/api/order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    response.json().then(data => {
                        if (response.status !== 200) {
                            alert(data.message);
                        } else {
                            location.reload();
                        }
                    });
                })
        }
    
    </script>
</body>
</html>
