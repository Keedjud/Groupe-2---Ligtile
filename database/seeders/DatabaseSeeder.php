<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Company;
use App\Models\Collection;
use App\Models\Label;
use App\Models\Trophee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        $adminUser = User::create([
            'first_name' => 'Admin',
            'last_name' => 'HUG',
            'email' => 'admin@hug.ch',
            'password' => bcrypt(env('ADMIN_PASSWORD', 'password123')),
        ]);

        // ===== ADDRESSES =====
        $addressUbs      = Address::create(['street' => 'Rue de la Banque',    'number' => '1',  'postal_code' => '1200', 'city' => 'Genève']);
        $addressCoop     = Address::create(['street' => 'Avenue Coop',         'number' => '50', 'postal_code' => '8050', 'city' => 'Zurich']);
        $addressNestle   = Address::create(['street' => 'Route Nestlé',        'number' => '77', 'postal_code' => '1800', 'city' => 'Vevey']);
        $addressRolex    = Address::create(['street' => 'Rue de la Manufacture','number' => '3',  'postal_code' => '1204', 'city' => 'Genève']);
        $addressMigros   = Address::create(['street' => 'Limmatstrasse',       'number' => '152','postal_code' => '8005', 'city' => 'Zurich']);
        $addressSwisscom = Address::create(['street' => 'Alte Tiefenaustrasse','number' => '6',  'postal_code' => '3050', 'city' => 'Berne']);
        $addressLaPoste  = Address::create(['street' => 'Wankdorfallee',       'number' => '4',  'postal_code' => '3030', 'city' => 'Berne']);
        $addressSwatch   = Address::create(['street' => 'Nicolas G. Hayek',    'number' => '1',  'postal_code' => '2502', 'city' => 'Bienne']);
        $addressLaTour   = Address::create(['street' => 'Avenue J.-D. Maillard','number' => '3', 'postal_code' => '1217', 'city' => 'Meyrin']);
        $addressSig      = Address::create(['street' => 'Chemin du Château-Bloch','number' => '2','postal_code' => '1219', 'city' => 'Le Lignon']);

        // ===== COMPANIES (10) =====
        $ubs      = Company::create(['name' => 'UBS',              'address_id' => $addressUbs->id,      'email' => 'contact@ubs.ch',       'phone_number' => '+41 44 234 85 00', 'nb_employee' => 5000]);
        $coop     = Company::create(['name' => 'Coop',             'address_id' => $addressCoop->id,     'email' => 'contact@coop.ch',      'phone_number' => '+41 44 724 72 47', 'nb_employee' => 8000]);
        $nestle   = Company::create(['name' => 'Nestlé',           'address_id' => $addressNestle->id,   'email' => 'contact@nestle.ch',    'phone_number' => '+41 21 924 24 24', 'nb_employee' => 3000]);
        $rolex    = Company::create(['name' => 'Rolex',            'address_id' => $addressRolex->id,    'email' => 'contact@rolex.ch',     'phone_number' => '+41 22 302 22 00', 'nb_employee' => 800]);
        $migros   = Company::create(['name' => 'Migros',           'address_id' => $addressMigros->id,   'email' => 'contact@migros.ch',    'phone_number' => '+41 58 570 00 00', 'nb_employee' => 6000]);
        $swisscom = Company::create(['name' => 'Swisscom',         'address_id' => $addressSwisscom->id, 'email' => 'contact@swisscom.ch',  'phone_number' => '+41 58 221 21 11', 'nb_employee' => 4500]);
        $laPoste  = Company::create(['name' => 'La Poste',         'address_id' => $addressLaPoste->id,  'email' => 'contact@poste.ch',     'phone_number' => '+41 58 338 00 00', 'nb_employee' => 3500]);
        $swatch   = Company::create(['name' => 'Swatch',           'address_id' => $addressSwatch->id,   'email' => 'contact@swatch.ch',    'phone_number' => '+41 32 343 90 00', 'nb_employee' => 900]);
        $laTour   = Company::create(['name' => 'Hôpital de La Tour','address_id' => $addressLaTour->id,  'email' => 'contact@latour.ch',    'phone_number' => '+41 22 719 61 11', 'nb_employee' => 150]);
        $sig      = Company::create(['name' => 'SIG',              'address_id' => $addressSig->id,      'email' => 'contact@sig-ge.ch',    'phone_number' => '+41 22 420 80 00', 'nb_employee' => 45]);

        // ===== COLLECTIONS =====
        // Logo local pour UBS, Coop, Nestlé — null pour les autres (placeholder)

        // UBS (2021, 2023, 2024, 2025 terminées + 2026 en cours)
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'address_id' => $addressUbs->id, 'start_date' => '2021-02-01', 'end_date' => '2021-02-28', 'primary_color' => '#EB001B', 'secondary_color' => '#c44444', 'logo_url' => '/images/UBS_logo.png',                      'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2021',   'public_token' => Str::random(32), 'nb_registered' => 163]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'address_id' => $addressUbs->id, 'start_date' => '2023-02-01', 'end_date' => '2023-02-28', 'primary_color' => '#EB001B', 'secondary_color' => '#c44444', 'logo_url' => '/images/UBS_logo.png',                      'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2023',   'public_token' => Str::random(32), 'nb_registered' => 250]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'address_id' => $addressUbs->id, 'start_date' => '2024-03-01', 'end_date' => '2024-03-31', 'primary_color' => '#EB001B', 'secondary_color' => '#c44444', 'logo_url' => '/images/UBS_logo.png',                      'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2024',   'public_token' => Str::random(32), 'nb_registered' => 300]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'address_id' => $addressUbs->id, 'start_date' => '2025-04-01', 'end_date' => '2025-04-30', 'primary_color' => '#EB001B', 'secondary_color' => '#c44444', 'logo_url' => '/images/UBS_logo.png',                      'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2025',   'public_token' => Str::random(32), 'nb_registered' => 320]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'address_id' => $addressUbs->id, 'start_date' => '2026-05-01', 'end_date' => '2026-06-30', 'primary_color' => '#EB001B', 'secondary_color' => '#c44444', 'logo_url' => '/images/UBS_logo.png',                      'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2026',   'public_token' => Str::random(32), 'nb_registered' => 150]);

        // Coop (2022, 2023, 2024, 2025 terminées + 2026 en cours)
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'address_id' => $addressCoop->id, 'start_date' => '2022-05-01', 'end_date' => '2022-05-31', 'primary_color' => '#FF6B35', 'secondary_color' => '#c44444', 'logo_url' => '/images/Coop_logo.png',                     'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2022',  'public_token' => Str::random(32), 'nb_registered' => 169]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'address_id' => $addressCoop->id, 'start_date' => '2023-05-01', 'end_date' => '2023-05-31', 'primary_color' => '#FF6B35', 'secondary_color' => '#c44444', 'logo_url' => '/images/Coop_logo.png',                     'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2023',  'public_token' => Str::random(32), 'nb_registered' => 180]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'address_id' => $addressCoop->id, 'start_date' => '2024-06-01', 'end_date' => '2024-06-30', 'primary_color' => '#FF6B35', 'secondary_color' => '#c44444', 'logo_url' => '/images/Coop_logo.png',                     'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2024',  'public_token' => Str::random(32), 'nb_registered' => 200]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'address_id' => $addressCoop->id, 'start_date' => '2025-07-01', 'end_date' => '2025-08-15', 'primary_color' => '#FF6B35', 'secondary_color' => '#c44444', 'logo_url' => '/images/Coop_logo.png',                     'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2025',  'public_token' => Str::random(32), 'nb_registered' => 220]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'address_id' => $addressCoop->id, 'start_date' => '2026-06-01', 'end_date' => '2026-07-15', 'primary_color' => '#FF6B35', 'secondary_color' => '#c44444', 'logo_url' => '/images/Coop_logo.png',                     'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2026',  'public_token' => Str::random(32), 'nb_registered' => 110]);

        // Nestlé (2021, 2022, 2023 terminées + 2026 en cours)
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'address_id' => $addressNestle->id, 'start_date' => '2021-05-15', 'end_date' => '2021-05-31', 'primary_color' => '#6B4423', 'secondary_color' => '#c44444', 'logo_url' => '/images/Nestle-Logo-3126327959 2-1.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2021','public_token' => Str::random(32), 'nb_registered' => 171]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'address_id' => $addressNestle->id, 'start_date' => '2022-03-15', 'end_date' => '2022-03-31', 'primary_color' => '#6B4423', 'secondary_color' => '#c44444', 'logo_url' => '/images/Nestle-Logo-3126327959 2-1.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2022','public_token' => Str::random(32), 'nb_registered' => 146]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'address_id' => $addressNestle->id, 'start_date' => '2023-03-15', 'end_date' => '2023-03-31', 'primary_color' => '#6B4423', 'secondary_color' => '#c44444', 'logo_url' => '/images/Nestle-Logo-3126327959 2-1.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2023','public_token' => Str::random(32), 'nb_registered' => 350]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'address_id' => $addressNestle->id, 'start_date' => '2026-05-15', 'end_date' => '2026-07-15', 'primary_color' => '#6B4423', 'secondary_color' => '#c44444', 'logo_url' => '/images/Nestle-Logo-3126327959 2-1.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2026','public_token' => Str::random(32), 'nb_registered' => 180]);

        // Rolex (2021, 2022, 2023, 2025 terminées)
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'address_id' => $addressRolex->id, 'start_date' => '2021-04-01', 'end_date' => '2021-04-30', 'primary_color' => '#000000', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2021',  'public_token' => Str::random(32), 'nb_registered' => 172]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'address_id' => $addressRolex->id, 'start_date' => '2022-06-01', 'end_date' => '2022-06-30', 'primary_color' => '#000000', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2022',  'public_token' => Str::random(32), 'nb_registered' => 158]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'address_id' => $addressRolex->id, 'start_date' => '2023-07-01', 'end_date' => '2023-07-31', 'primary_color' => '#000000', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2023',  'public_token' => Str::random(32), 'nb_registered' => 140]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'address_id' => $addressRolex->id, 'start_date' => '2025-08-01', 'end_date' => '2025-08-31', 'primary_color' => '#000000', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2025',  'public_token' => Str::random(32), 'nb_registered' => 93]);

        // Migros (2025, 2026 — pas de logo local → placeholder)
        Collection::create(['company_id' => $migros->id, 'user_id' => $adminUser->id, 'address_id' => $addressMigros->id, 'start_date' => '2025-09-01', 'end_date' => '2025-09-30', 'primary_color' => '#FF6600', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/migros-2025', 'public_token' => Str::random(32), 'nb_registered' => 400]);
        Collection::create(['company_id' => $migros->id, 'user_id' => $adminUser->id, 'address_id' => $addressMigros->id, 'start_date' => '2026-04-01', 'end_date' => '2026-05-15', 'primary_color' => '#FF6600', 'secondary_color' => '#c44444', 'logo_url' => null,                                       'onedoc_url' => 'https://onedoc.hug.ch/collections/migros-2026', 'public_token' => Str::random(32), 'nb_registered' => 210]);

        // Swisscom (2024, 2026 en cours — pas de logo local → placeholder)
        Collection::create(['company_id' => $swisscom->id, 'user_id' => $adminUser->id, 'address_id' => $addressSwisscom->id, 'start_date' => '2024-10-01', 'end_date' => '2024-10-31', 'primary_color' => '#0066CC', 'secondary_color' => '#c44444', 'logo_url' => null,                                    'onedoc_url' => 'https://onedoc.hug.ch/collections/swisscom-2024','public_token' => Str::random(32), 'nb_registered' => 280]);
        Collection::create(['company_id' => $swisscom->id, 'user_id' => $adminUser->id, 'address_id' => $addressSwisscom->id, 'start_date' => '2026-05-01', 'end_date' => '2026-06-30', 'primary_color' => '#0066CC', 'secondary_color' => '#c44444', 'logo_url' => null,                                    'onedoc_url' => 'https://onedoc.hug.ch/collections/swisscom-2026','public_token' => Str::random(32), 'nb_registered' => 170]);

        // La Poste (2025 — pas de logo local → placeholder)
        Collection::create(['company_id' => $laPoste->id, 'user_id' => $adminUser->id, 'address_id' => $addressLaPoste->id, 'start_date' => '2025-01-15', 'end_date' => '2025-02-15', 'primary_color' => '#FFCC00', 'secondary_color' => '#c44444', 'logo_url' => null,                                     'onedoc_url' => 'https://onedoc.hug.ch/collections/poste-2025',  'public_token' => Str::random(32), 'nb_registered' => 310]);

        // Swatch (2026 — pas de logo local → placeholder)
        Collection::create(['company_id' => $swatch->id, 'user_id' => $adminUser->id, 'address_id' => $addressSwatch->id, 'start_date' => '2026-03-01', 'end_date' => '2026-03-31', 'primary_color' => '#FF0000', 'secondary_color' => '#c44444', 'logo_url' => null,                                     'onedoc_url' => 'https://onedoc.hug.ch/collections/swatch-2026', 'public_token' => Str::random(32), 'nb_registered' => 120]);

        // Hôpital de La Tour (2024 — pas de logo local → placeholder)
        Collection::create(['company_id' => $laTour->id, 'user_id' => $adminUser->id, 'address_id' => $addressLaTour->id, 'start_date' => '2024-04-01', 'end_date' => '2024-04-30', 'primary_color' => '#0099CC', 'secondary_color' => '#c44444', 'logo_url' => null,                                   'onedoc_url' => 'https://onedoc.hug.ch/collections/latour-2024', 'public_token' => Str::random(32), 'nb_registered' => 90]);

        // SIG (2026 — pas de logo local → placeholder)
        Collection::create(['company_id' => $sig->id, 'user_id' => $adminUser->id, 'address_id' => $addressSig->id, 'start_date' => '2026-02-01', 'end_date' => '2026-02-28', 'primary_color' => '#339966', 'secondary_color' => '#c44444', 'logo_url' => null,                                        'onedoc_url' => 'https://onedoc.hug.ch/collections/sig-2026',    'public_token' => Str::random(32), 'nb_registered' => 60]);

        // ===== LABELS =====
        $label2023 = Label::create(['name' => 'Label CTS 2023']);
        $ubs->labels()->attach($label2023->id,    ['start_date' => '2023-02-01', 'end_date' => '2025-02-01']);
        $coop->labels()->attach($label2023->id,   ['start_date' => '2023-05-01', 'end_date' => '2025-05-01']);
        $nestle->labels()->attach($label2023->id, ['start_date' => '2023-03-15', 'end_date' => '2025-03-15']);
        $rolex->labels()->attach($label2023->id,  ['start_date' => '2023-07-01', 'end_date' => '2025-07-01']);

        $label2024 = Label::create(['name' => 'Label CTS 2024']);
        $ubs->labels()->attach($label2024->id,      ['start_date' => '2024-03-01', 'end_date' => '2026-03-01']);
        $coop->labels()->attach($label2024->id,     ['start_date' => '2024-06-01', 'end_date' => '2026-06-01']);
        $swisscom->labels()->attach($label2024->id, ['start_date' => '2024-10-01', 'end_date' => '2026-10-01']);
        $laTour->labels()->attach($label2024->id,   ['start_date' => '2024-04-01', 'end_date' => '2026-04-01']);

        $label2025 = Label::create(['name' => 'Label CTS 2025']);
        $ubs->labels()->attach($label2025->id,     ['start_date' => '2025-04-01', 'end_date' => '2027-04-01']);
        $coop->labels()->attach($label2025->id,    ['start_date' => '2025-07-01', 'end_date' => '2027-07-01']);
        $migros->labels()->attach($label2025->id,  ['start_date' => '2025-09-01', 'end_date' => '2027-09-01']);
        $laPoste->labels()->attach($label2025->id, ['start_date' => '2025-01-15', 'end_date' => '2027-01-15']);

        $label2026 = Label::create(['name' => 'Label CTS 2026']);
        $ubs->labels()->attach($label2026->id,      ['start_date' => '2026-05-01', 'end_date' => '2028-05-01']);
        $nestle->labels()->attach($label2026->id,   ['start_date' => '2026-05-15', 'end_date' => '2028-05-15']);
        $migros->labels()->attach($label2026->id,   ['start_date' => '2026-04-01', 'end_date' => '2028-04-01']);
        $swisscom->labels()->attach($label2026->id, ['start_date' => '2026-05-01', 'end_date' => '2028-05-01']);
        $swatch->labels()->attach($label2026->id,   ['start_date' => '2026-03-01', 'end_date' => '2028-03-01']);
        $sig->labels()->attach($label2026->id,      ['start_date' => '2026-02-01', 'end_date' => '2028-02-01']);

        // ===== TROPHEES =====
        $tropheeOr2021     = Trophee::create(['name' => 'Trophée Or 2021',     'year' => 2021]); $rolex->trophees()->attach($tropheeOr2021->id,      ['rank' => 1]);
        $tropheeArgent2021 = Trophee::create(['name' => 'Trophée Argent 2021', 'year' => 2021]); $nestle->trophees()->attach($tropheeArgent2021->id,  ['rank' => 2]);
        $tropheeBronze2021 = Trophee::create(['name' => 'Trophée Bronze 2021', 'year' => 2021]); $ubs->trophees()->attach($tropheeBronze2021->id,     ['rank' => 3]);

        $tropheeOr2022     = Trophee::create(['name' => 'Trophée Or 2022',     'year' => 2022]); $coop->trophees()->attach($tropheeOr2022->id,        ['rank' => 1]);
        $tropheeArgent2022 = Trophee::create(['name' => 'Trophée Argent 2022', 'year' => 2022]); $rolex->trophees()->attach($tropheeArgent2022->id,   ['rank' => 2]);
        $tropheeBronze2022 = Trophee::create(['name' => 'Trophée Bronze 2022', 'year' => 2022]); $nestle->trophees()->attach($tropheeBronze2022->id,  ['rank' => 3]);

        $tropheeOr2023     = Trophee::create(['name' => 'Trophée Or 2023',     'year' => 2023]); $nestle->trophees()->attach($tropheeOr2023->id,      ['rank' => 1]);
        $tropheeArgent2023 = Trophee::create(['name' => 'Trophée Argent 2023', 'year' => 2023]); $ubs->trophees()->attach($tropheeArgent2023->id,     ['rank' => 2]);
        $tropheeBronze2023 = Trophee::create(['name' => 'Trophée Bronze 2023', 'year' => 2023]); $coop->trophees()->attach($tropheeBronze2023->id,    ['rank' => 3]);

        $tropheeOr2024     = Trophee::create(['name' => 'Trophée Or 2024',     'year' => 2024]); $ubs->trophees()->attach($tropheeOr2024->id,         ['rank' => 1]);
        $tropheeArgent2024 = Trophee::create(['name' => 'Trophée Argent 2024', 'year' => 2024]); $coop->trophees()->attach($tropheeArgent2024->id,    ['rank' => 2]);
        $tropheeBronze2024 = Trophee::create(['name' => 'Trophée Bronze 2024', 'year' => 2024]); $rolex->trophees()->attach($tropheeBronze2024->id,   ['rank' => 3]);

        $tropheeOr2025     = Trophee::create(['name' => 'Trophée Or 2025',     'year' => 2025]); $ubs->trophees()->attach($tropheeOr2025->id,         ['rank' => 1]);
        $tropheeArgent2025 = Trophee::create(['name' => 'Trophée Argent 2025', 'year' => 2025]); $coop->trophees()->attach($tropheeArgent2025->id,    ['rank' => 2]);
        $tropheeBronze2025 = Trophee::create(['name' => 'Trophée Bronze 2025', 'year' => 2025]); $rolex->trophees()->attach($tropheeBronze2025->id,   ['rank' => 3]);

        $tropheeOr2026     = Trophee::create(['name' => 'Trophée Or 2026',     'year' => 2026]); $nestle->trophees()->attach($tropheeOr2026->id,      ['rank' => 1]);
        $tropheeArgent2026 = Trophee::create(['name' => 'Trophée Argent 2026', 'year' => 2026]); $ubs->trophees()->attach($tropheeArgent2026->id,     ['rank' => 2]);
        $tropheeBronze2026 = Trophee::create(['name' => 'Trophée Bronze 2026', 'year' => 2026]); $coop->trophees()->attach($tropheeBronze2026->id,    ['rank' => 3]);
    }
}
