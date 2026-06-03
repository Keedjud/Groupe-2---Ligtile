<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kit de communication</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f0f5;font-family:Arial,Helvetica,sans-serif;color:#2b2b2b;">
    <div style="max-width:600px;margin:0 auto;padding:24px;">
        @php
            // Logo
            $logoSrc = $logoUrl ?: ($logoData ? $message->embedData($logoData, 'logo', $logoMime) : null);
        @endphp
        <div style="background-color:{{ $couleurPrimaire }};border-radius:16px 16px 0 0;padding:28px 24px;text-align:center;">
            @if ($logoSrc)
                <img src="{{ $logoSrc }}" alt="{{ $entreprise }}" style="max-height:56px;max-width:200px;margin-bottom:12px;display:inline-block;">
                <br>
            @endif
            <h1 style="margin:0;color:#ffffff;font-size:22px;">Votre kit de communication</h1>
            <p style="margin:6px 0 0;color:#ffffff;font-size:14px;opacity:.9;">Collecte de sang - {{ $entreprise }}</p>
        </div>

        <div style="background-color:#ffffff;border:1px solid #e6e0e8;border-top:none;border-radius:0 0 16px 16px;padding:28px 24px;">
            <p style="font-size:15px;line-height:1.6;margin-top:0;">
                Bonjour,
            </p>
            <p style="font-size:15px;line-height:1.6;">
                Voici les informations pour votre collecte de sang organisée avec le Centre de
                Transfusion Sanguine. Vous trouverez ci-dessous le lien vers votre site dédié ainsi
                que, si disponible, votre kit de communication co-brandé à diffuser auprès de vos
                collaboratrices et collaborateurs.
            </p>
            @if ($dateDebut || $dateFin)
                <table role="presentation" style="width:100%;border-collapse:collapse;margin:20px 0;background-color:#f4f0f5;border-radius:8px;">
                    <tr>
                        <td style="padding:12px 16px;font-size:14px;">
                            <strong style="color:{{ $couleurPrimaire }};">Date de début :</strong> {{ $dateDebut ?: '-' }}
                        </td>
                        <td style="padding:12px 16px;font-size:14px;">
                            <strong style="color:{{ $couleurPrimaire }};">Date de fin :</strong> {{ $dateFin ?: '-' }}
                        </td>
                    </tr>
                </table>
            @endif

            <p style="font-size:15px;line-height:1.6;">
                Le site dédié à votre collecte, aux couleurs de <strong>{{ $entreprise }}</strong>,
                est accessible à vos collaboratrices et collaborateurs via le lien ci-dessous :
            </p>

            <div style="text-align:center;margin:28px 0;">
                <a href="{{ $lienCoBrande }}"
                   style="display:inline-block;background-color:{{ $couleurSecondaire }};color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;padding:14px 28px;border-radius:40px;">
                    Accéder au site de la collecte
                </a>
            </div>

            <p style="font-size:13px;line-height:1.6;color:#6b6b6b;word-break:break-all;">
                Ou copiez ce lien : <a href="{{ $lienCoBrande }}" style="color:{{ $couleurPrimaire }};">{{ $lienCoBrande }}</a>
            </p>

            @if ($lienKitComm)
                <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
                <p style="font-size:15px;line-height:1.6;">
                    Votre <strong>kit de communication co-brandé</strong> (affiches, visuels, etc.) est disponible sur KDrive :
                </p>
                <div style="text-align:center;margin:20px 0;">
                    <a href="{{ $lienKitComm }}"
                       style="display:inline-block;background-color:{{ $couleurPrimaire }};color:#ffffff;text-decoration:none;font-size:15px;font-weight:bold;padding:14px 28px;border-radius:40px;">
                        Télécharger le kit de communication
                    </a>
                </div>
                <p style="font-size:13px;line-height:1.6;color:#6b6b6b;word-break:break-all;">
                    Ou copiez ce lien : <a href="{{ $lienKitComm }}" style="color:{{ $couleurPrimaire }};">{{ $lienKitComm }}</a>
                </p>
            @endif

            <hr style="border:none;border-top:1px solid #eee;margin:24px 0;">
            <p style="font-size:12px;color:#9a9a9a;line-height:1.5;margin-bottom:0;">
                Ce site est la réalisation d'un projet d'étudiant·es de la HEIG-VD en collaboration
                avec les HUG. Il s'agit d'un projet pédagogique sans lien officiel avec les HUG ou le CTS.
            </p>
        </div>
    </div>
</body>
</html>
