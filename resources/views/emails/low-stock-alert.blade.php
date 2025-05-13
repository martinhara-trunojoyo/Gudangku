<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Stock Alert</title>
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
            background-color: #ff0000;
            color: white;
            padding: 15px 20px;
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
            background-color: #fff0f0;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border: 2px dashed #ff0000;
        }
        .warning {
            color: #ff0000;
            font-weight: bold;
            font-size: 18px;
        }
        .action-button {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>⚠️ URGENT: LOW STOCK ALERT ⚠️</h2>
    </div>
    
    <div class="content">
        <p>Hello {{ $recipientName }},</p>
        
        <p class="warning">This is an URGENT notification that stock has fallen below the minimum threshold in <strong>{{ $umkmName }}</strong>.</p>
        
        <div class="stock-info">
            <p><strong>Product:</strong> {{ $productName }}</p>
            <p><strong>Current Stock Level:</strong> {{ $currentStock }} {{ $unit }}</p>
            <p><strong>Minimum Stock Threshold:</strong> {{ $minimumStock }} {{ $unit }}</p>
        </div>
        
        <p>Please take immediate action to restock this product to avoid stockouts and potential business disruption.</p>
        
        <p>You can:</p>
        <ul>
            <li>Place an order with your supplier</li>
            <li>Check if there are any pending inbound shipments</li>
            <li>Consider adjusting your minimum stock threshold if necessary</li>
        </ul>
        
        <p>Thank you for your immediate attention to this matter.</p>
        
        <p>Regards,<br>{{ $umkmName }} Inventory System</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html> 