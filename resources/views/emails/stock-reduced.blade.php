<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Reduced Notification</title>
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
            background-color: #f44336;
            color: white;
            padding: 10px 20px;
            border-radius: 5px 5px 0 0;
        }
        .content {
            border: 1px solid #ddd;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        .stock-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            color: #f44336;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Stock Reduction Alert</h2>
    </div>
    
    <div class="content">
        <p>Hello {{ $recipientName }},</p>
        
        <p>This is to inform you that stock has been removed from inventory in <strong>{{ $umkmName }}</strong>.</p>
        
        <div class="stock-info">
            <p><strong>Product:</strong> {{ $productName }}</p>
            <p><strong>Quantity removed:</strong> {{ abs($quantity) }} {{ $unit }}</p>
            <p><strong>Current stock level:</strong> {{ $currentStock }} {{ $unit }}</p>
            <p><strong>Destination:</strong> {{ $destination }}</p>
            <p><strong>Action by:</strong> {{ $staffName }}</p>
        </div>
        
        @if($currentStock <= 5)
        <p class="warning">WARNING: Stock level is very low! Please consider restocking this item soon.</p>
        @endif
        
        <p>Please log in to the inventory management system for more details.</p>
        
        <p>Thank you,<br>{{ $umkmName }} Inventory System</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html> 