<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre demande de collecte de sang</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap');
    </style>
</head>
<body style="margin:0;padding:0;background-color:#f4f0f5;font-family:'DM Sans',Arial,Helvetica,sans-serif;color:#361136;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        <div style="background-color:#f7e8fb;border-radius:16px 16px 0 0;padding:28px 24px;text-align:center;">
            <img src="{{ $message->embed(public_path('images/logo-hug.png')) }}" alt="HUG" style="max-height:56px;max-width:200px;margin-bottom:12px;display:inline-block;">
            <br>
            <h1 style="margin:0;color:#361136;font-size:22px;">Demande bien reçue</h1>
            <p style="margin:6px 0 0;color:#681764;font-size:14px;">Collecte de sang</p>
        </div>

        <div style="background-color:#ffffff;border:1px solid #e6e0e8;border-top:none;border-radius:0 0 16px 16px;padding:28px 24px;">
            <p style="font-size:15px;line-height:1.6;margin-top:0;">
                Bonjour,
            </p>
            <p style="font-size:15px;line-height:1.6;">
                Merci pour votre demande d'organisation d'une collecte de sang au sein de
                <strong style="color:#681764;">{{ $entreprise }}</strong>. Notre équipe vous
                recontactera prochainement par e-mail ou par téléphone pour convenir des détails.
            </p>
            <p style="font-size:15px;line-height:1.6;margin-bottom:0;">
                À bientôt,<br>
                <strong>L'équipe Collectes du HUG</strong>
            </p>

            <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
            <p style="font-size:12px;color:#9a9a9a;line-height:1.5;margin-bottom:0;">
                Ce site est la réalisation d'un projet d'étudiant·es de la HEIG-VD en collaboration
                avec les HUG. Il s'agit d'un projet pédagogique sans lien officiel avec les HUG ou le CTS.
            </p>
        </div>
    </div>
</body>
</html>
