<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap');
    </style>
</head>
<body style="margin:0;padding:0;font-family:'DM Sans',Arial,Helvetica,sans-serif;background:#f5f5f5;color:#361136;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background:#f7e8fb;border-radius:8px 8px 0 0;padding:24px;">
            <h2 style="margin:0;color:#361136;font-size:20px;font-weight:700;">Contact {{ $company_name}}</h2>
        </div>

        <div style="background:#ffffff;border:1px solid #e5e7eb;padding:24px;">
            <div style="margin-bottom:20px;">
                <span style="display:block;font-weight:700;margin-bottom:6px;">Entreprise :</span>
                <p style="margin:0;color:#4b5563;">{{ $company_name }}</p>
            </div>

            <div style="margin-bottom:20px;">
                <span style="display:block;font-weight:700;margin-bottom:6px;">Email :</span>
                <p style="margin:0;color:#4b5563;">{{ $email }}</p>
            </div>

            <div style="margin-bottom:20px;">
                <span style="display:block;font-weight:700;margin-bottom:6px;">Message :</span>
                <p style="margin:0;color:#4b5563;white-space:pre-line;">{{ $messageContenu }}</p>
            </div>
        </div>

        <div style="background:#ede9fe;border-radius:0 0 8px 8px;padding:16px;text-align:center;">
            <p style="margin:0;font-size:12px;color:#6b7280;">Message automatique envoyé depuis la page d'informations.</p>
        </div>
    </div>
</body>
</html>
