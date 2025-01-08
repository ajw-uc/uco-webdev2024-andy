<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            background: #d3d3d3;
            font-family: Arial, Helvetica, sans-serif;
        }
        .container {
            margin: 3em;
            padding: 1em 2em;
            background: #f5f5f5;
            border-radius:5px;
            box-shadow: 2px 1px 3px #a2a2a2;
        }
        table td {
            vertical-align:middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>New products for you!</h1>
        <table style="width:100%">
            @foreach($products as $product)
            <tr>
                <td style="width:120px">
                    <img src="{{ asset($product->image) }}" style="max-width:100px;max-height:100px">
                </td>
                <td>
                    {{ $product->name }}
                </td>
                <td style="width:100px">
                    <a href="{{ route('products.show', ['id' => $product->id]) }}">
                        View product
                    </a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
