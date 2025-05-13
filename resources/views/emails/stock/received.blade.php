@component('mail::message')
# Stock Update: New Items Received

**{{ $quantity }} {{ $unit }}** of **{{ $productName }}** have been added to inventory.

## Stock Details
- **Product**: {{ $productName }}
- **Quantity received**: {{ $quantity }} {{ $unit }}
- **Current stock**: {{ $newStock }} {{ $unit }}
- **Supplier**: {{ $supplierName }}
- **Recorded by**: {{ $addedBy }}

This stock was added on {{ now()->format('F j, Y \a\t g:i A') }}.

@component('mail::button', ['url' => $url])
View Inventory
@endcomponent

Thank you for using our inventory management system!

Regards,<br>
{{ config('app.name') }}
@endcomponent 