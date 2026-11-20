<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #0b1120;">New Customer Lead Received</h2>
    <p><strong>Reference:</strong> {{ $lead->reference_number }}</p>
    <p><strong>Name:</strong> {{ $lead->full_name }}<br>
       <strong>Mobile:</strong> {{ $lead->mobile_number }}<br>
       <strong>Email:</strong> {{ $lead->email ?: '-' }}<br>
       <strong>Location:</strong> {{ $lead->city }}, {{ $lead->state }}</p>
    <p><strong>Vehicle:</strong> {{ trim($lead->vehicle_brand . ' ' . $lead->vehicle_model . ' ' . $lead->vehicle_year) ?: '-' }}<br>
       <strong>Battery:</strong> {{ $lead->battery_requirement ?: '-' }}<br>
       <strong>Type:</strong> {{ $lead->enquiry_type_label }}</p>
    <p><strong>Remarks:</strong> {{ $lead->remarks ?: '-' }}</p>
    <p><strong>Attribution:</strong> Source: {{ $lead->utm_source ?: 'direct' }} | Campaign: {{ $lead->utm_campaign ?: '-' }}</p>
    <p><a href="{{ url('/admin/leads/' . $lead->id) }}" style="display:inline-block;padding:10px 18px;background:#f59e0b;color:#0b1120;text-decoration:none;font-weight:bold;border-radius:6px;">View Lead in Admin</a></p>
</body>
</html>
