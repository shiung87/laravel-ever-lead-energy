<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: #0b1120; color: #ffffff; padding: 20px 24px; border-radius: 8px 8px 0 0;">
        <h2 style="margin: 0; color: #f59e0b;">EVERLEAD ENERGY</h2>
        <p style="margin: 4px 0 0; font-size: 13px; color: #94a3b8;">Authorized Dealer Partnership</p>
    </div>
    <div style="border: 1px solid #e5e7eb; border-top: none; padding: 24px; border-radius: 0 0 8px 8px;">
        <p>Dear <strong>{{ $application->contact_person }}</strong>,</p>
        <p>Thank you for applying to become an Authorized Dealer with Everlead Energy for <strong>{{ $application->business_name }}</strong>.</p>
        <div style="background: #f8fafc; border-left: 4px solid #f59e0b; padding: 12px 16px; margin: 16px 0;">
            <p style="margin: 0;"><strong>Application Reference:</strong> {{ $application->reference_number }}</p>
            <p style="margin: 4px 0 0;"><strong>Partnership Type:</strong> {{ $application->partnership_type }}</p>
            <p style="margin: 4px 0 0;"><strong>Status:</strong> {{ $application->status_label }}</p>
        </div>
        <p>Our Dealer Partnership team is reviewing your application and will contact you within 1–2 business days.</p>
        <p style="margin-top: 24px; font-size: 13px; color: #6b7280;">Best regards,<br><strong>Everlead Energy Partnership Team</strong></p>
    </div>
</body>
</html>
