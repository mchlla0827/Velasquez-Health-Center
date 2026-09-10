<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Your Password</title>
    <!--[if mso]>
    <style type="text/css">
        body, table, td, a { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #F1F5F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; -webkit-text-size-adjust: 100%;">

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F1F5F9; padding: 48px 16px;">
    <tr>
        <td align="center">
            
            <!-- MAIN WRAPPER -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px; background-color: #FFFFFF; border-radius: 16px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);">
                
                <!-- TOP TEAL ACCENT BAR -->
                <tr>
                    <td style="height: 4px; background-color: #0D9488; font-size: 0; line-height: 0;">&nbsp;</td>
                </tr>

                <!-- CENTERED INSTITUTIONAL HEADER -->
                <tr>
                    <td align="center" style="padding: 36px 32px 24px; background-color: #FAFCFC; border-bottom: 1px solid #EEF2F6;">
                        
                        <!-- LOGO EMBED -->
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 14px;">
                            <tr>
                                <td align="center">
                                    @php
                                        $logoSrc = file_exists(public_path('bhclogo.jpg')) 
                                            ? (isset($message) ? $message->embed(public_path('bhclogo.jpg')) : asset('bhclogo.jpg'))
                                            : null;
                                    @endphp

                                    @if($logoSrc)
                                        <img src="{{ $logoSrc }}" width="56" height="56" alt="Logo" style="display: block; border-radius: 12px; border: 1px solid #E2E8F0; object-fit: cover;">
                                    @else
                                        <div style="width: 56px; height: 56px; border-radius: 12px; background-color: #CCFBF1; border: 1px solid #99F6E4; color: #0F766E; font-size: 22px; font-weight: 700; line-height: 56px; text-align: center;">
                                            +
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <!-- FACILITY TITLE & BADGE -->
                        <div style="font-size: 17px; font-weight: 700; color: #0F172A; letter-spacing: -0.2px; line-height: 1.3;">
                            Velasquez Health Center
                        </div>
                        <div style="font-size: 12.5px; font-weight: 500; color: #64748B; margin-top: 3px;">
                            Health Information System (HIS)
                        </div>
                        
                        <div style="margin-top: 12px;">
                            <span style="display: inline-block; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px; color: #0F766E; background-color: #CCFBF1; border: 1px solid #99F6E4; padding: 3px 10px; border-radius: 9999px;">
                                Security Notification
                            </span>
                        </div>

                    </td>
                </tr>

                <!-- BODY CONTENT -->
                <tr>
                    <td style="padding: 36px 36px 28px;">
                        
                        <h1 style="margin: 0 0 12px; font-size: 20px; font-weight: 700; color: #0F172A; letter-spacing: -0.3px;">
                            Password Reset Request
                        </h1>
                        
                        <p style="margin: 0 0 20px; font-size: 14.5px; line-height: 1.65; color: #475569;">
                            We received a request to update the password associated with your account. Click the secure verification button below to set new credentials:
                        </p>

                        <!-- CTA BUTTON -->
                        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0;">
                            <tr>
                                <td align="center" style="border-radius: 8px; background-color: #0D9488;">
                                    <a href="{{ $url }}" target="_blank" style="display: inline-block; padding: 13px 30px; font-size: 14px; font-weight: 600; color: #FFFFFF; text-decoration: none; border-radius: 8px; border: 1px solid #0D9488; letter-spacing: 0.2px;">
                                        Reset Password &rarr;
                                    </a>
                                </td>
                            </tr>
                        </table>

                        <!-- EXPIRATION ALERT CARD -->
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #F0FDFA; border-left: 3px solid #0D9488; border-radius: 0 8px 8px 0; margin-bottom: 24px;">
                            <tr>
                                <td style="padding: 14px 16px;">
                                    <p style="margin: 0; font-size: 13px; line-height: 1.55; color: #115E59;">
                                        <strong>Security Note:</strong> This reset link will automatically expire in <strong>{{ $expireMinutes }} minutes</strong>. If you did not make this request, you can disregard this email; your account security is not affected.
                                    </p>
                                </td>
                            </tr>
                        </table>

                        <!-- RAW URL FALLBACK -->
                        <p style="margin: 0 0 6px; font-size: 12px; font-weight: 600; color: #64748B;">
                            Having trouble with the button? Copy and paste this link:
                        </p>
                        <p style="margin: 0; font-size: 11.5px; line-height: 1.45; word-break: break-all; background-color: #F8FAFC; border: 1px solid #E2E8F0; padding: 10px 12px; border-radius: 6px;">
                            <a href="{{ $url }}" style="color: #0D9488; text-decoration: underline;">{{ $url }}</a>
                        </p>

                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td style="padding: 22px 36px 26px; background-color: #FAFCFC; border-top: 1px solid #EEF2F6;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td style="font-size: 11.5px; line-height: 1.5; color: #94A3B8;">
                                    This is an automated administrative notification sent from the Velasquez Health Center HIS portal. Please do not reply directly to this email.
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
            <!-- END MAIN WRAPPER -->

            <!-- SUB-FOOTER -->
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px; margin-top: 18px;">
                <tr>
                    <td align="center" style="font-size: 11.5px; color: #94A3B8;">
                        &copy; {{ date('Y') }} Velasquez Health Center &bull; All rights reserved.
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

</body>
</html>