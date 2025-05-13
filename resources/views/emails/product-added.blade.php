<!DOCTYPE html>
<html>
<head>
    <title>New Stock Added</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 3px;
            margin-top: 15px;
        }
        h2 {
            color: #4CAF50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $umkmName }} - Inventory Update</h1>
    </div>
    
    <div class="content">
        <p>Hello {{ $recipientName }},</p>
        
        <p>This is a notification that new stock has been added to your inventory by <strong>{{ $addedBy }}</strong>.</p>
        
        <h2>Product Details:</h2>
        
        <table>
            <tr>
                <th>Product Name</th>
                <td>{{ $productName }}</td>
            </tr>
            <tr>
                <th>Quantity Added</th>
                <td>{{ $quantity }} {{ $unit }}</td>
            </tr>
            <tr>
                <th>Current Stock</th>
                <td>{{ $newStock }} {{ $unit }}</td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td>{{ $supplierName }}</td>
            </tr>
        </table>
        
        <p>
            <a href="{{ url('/dashboard/inventory') }}" class="btn">View Inventory</a>
        </p>
        
        <p>Thank you for using our inventory management system.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $umkmName }}. All rights reserved.</p>
        <p>This is an automated email, please do not reply.</p>
    </div>
</body>
</html>
