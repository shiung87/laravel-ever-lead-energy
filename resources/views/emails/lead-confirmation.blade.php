<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #0b1120; color: #ffffff; padding: 20px 24px; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0; color: #f59e0b;">EVERLEAD ENERGY</h2>
        <p style="margin: 4px 0 0; font-size: 13px; color: #94a3b8;">Power Every Journey.</p>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Dear <strong>{{ $lead->full_name }}</strong>,</p>
        <p>Thank you for contacting Everlead Energy. We have received your enquiry and our battery specialist team will reach out to you shortly.</p>
        <div style="background: #f8fafc; border-left: 4px solid #f59e0b; padding: 12px 16px; margin: 16px 0;">
            <p style="margin: 0;"><strong>Reference Number:</strong> {{ $lead->reference_number }}</p>
            <p style="margin: 4px 0 0;"><strong>Enquiry Type:</strong> {{ $lead->enquiry_type_label }}</p>
            @if($lead->vehicle_brand || $lead->vehicle_model)
                <p style="margin: 4px 0 0;"><strong>Vehicle:</strong> {{ trim($lead->vehicle_brand . ' ' . $lead->vehicle_model . ' ' . $lead->vehicle_year) }}</p>
            @endif
            @if($lead->battery_requirement)
                <p style="margin: 4px 0 0;"><strong>Battery Requirement:</strong> {{ $lead->battery_requirement }}</p>
            @endif
        </div>
        <p>Need urgent assistance? Reply to this email or contact our team via WhatsApp for immediate dispatch support.</p>
        <p style="margin-top: 24px; font-size: 13px; color: #6b7280;">Best regards,<br><strong>Everlead Energy Customer Care</strong></p>
    </div>
</body>
</html>
