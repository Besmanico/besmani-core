<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message from {{ config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; -webkit-font-smoothing: antialiased;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f6f9;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="margin: 0 auto; max-width: 600px; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08); overflow: hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%); padding: 32px 40px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 600; letter-spacing: -0.5px;">{{ config('app.name') }}</h1>
                            <p style="margin: 8px 0 0 0; color: rgba(255,255,255,0.9); font-size: 14px;">Beauty & Professional Services</p>
                        </td>
                    </tr>
                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            @if(!empty($senderName))
                            <div style="margin-bottom: 24px; padding: 16px 20px; background-color: #f0f9ff; border-left: 4px solid #06b6d4; border-radius: 0 8px 8px 0;">
                                <p style="margin: 0; color: #0e7490; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">From</p>
                                <p style="margin: 4px 0 0 0; color: #164e63; font-size: 16px; font-weight: 600;">{{ $senderName }}</p>
                            </div>
                            @endif
                            <p style="margin: 0 0 16px 0; color: #64748b; font-size: 14px; line-height: 1.6;">Hello,</p>
                            <div style="color: #334155; font-size: 15px; line-height: 1.8; white-space: pre-line;">{{ $description }}</div>
                            <p style="margin: 24px 0 0 0; color: #64748b; font-size: 14px;">Best regards,<br><strong>{{ $senderName ?: config('app.name') }}</strong></p>
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                            <p style="margin: 0; color: #94a3b8; font-size: 12px;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
