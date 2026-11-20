<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #0b1120;">New Dealer Partnership Application</h2>
    <p><strong>Reference:</strong> {{ $application->reference_number }}</p>
    <p><strong>Business Name:</strong> {{ $application->business_name }} ({{ $application->registration_number }})<br>
       <strong>Contact Person:</strong> {{ $application->contact_person }}<br>
       <strong>Mobile:</strong> {{ $application->mobile_number }} | <strong>Email:</strong> {{ $application->email }}<br>
       <strong>Location:</strong> {{ $application->city }}, {{ $application->state }}<br>
       <strong>Business Type:</strong> {{ $application->business_type }} ({{ $application->years_in_operation }} yrs)<br>
       <strong>Est. Monthly Volume:</strong> {{ $application->estimated_monthly_volume }}</p>
    <p><a href="{{ url('/admin/dealers/' . $application->id) }}" style="display:inline-block;padding:10px 18px;background:#f59e0b;color:#0b1120;text-decoration:none;font-weight:bold;border-radius:6px;">Review Application</a></p>
</body>
</html>
