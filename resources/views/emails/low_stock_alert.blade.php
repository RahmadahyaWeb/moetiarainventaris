<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Low Stock Alert</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <p>Dear Admin,</p>
    <p>The stock for the following item is below the minimum stock threshold:</p>

    <table>
        <thead>
            <tr>
                <th style="width: 30%">Item Name</th>
                <th style="width: 30%">Item Code</th>
                <th>Current Stock</th>
                <th>Minimum Stock Required</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $items->nama_barang }}</td>
                <td>{{ $items->kode_barang }}</td>
                <td>{{ $items->last_stock }} {{ $items->unit->code }}</td>
                <td>{{ $items->minimum }} {{ $items->unit->code }}</td>
            </tr>
        </tbody>
    </table>

    <p>Please take necessary action to restock.</p>
    <p>Thank you!</p>
</body>

</html>
