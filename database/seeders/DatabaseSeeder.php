<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Collection;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Trophee;
use App\Models\User;
use App\Services\LabelService;
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
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);

        // ===== ADDRESSES =====
        $addressUbs      = Address::create(['street' => 'Rue de la Banque',        'number' => '1',   'postal_code' => '1200', 'city' => 'Genève']);
        $addressCoop     = Address::create(['street' => 'Avenue Coop',             'number' => '50',  'postal_code' => '8050', 'city' => 'Zurich']);
        $addressNestle   = Address::create(['street' => 'Route Nestlé',            'number' => '77',  'postal_code' => '1800', 'city' => 'Vevey']);
        $addressRolex    = Address::create(['street' => 'Rue de la Manufacture',   'number' => '3',   'postal_code' => '1204', 'city' => 'Genève']);
        $addressMigros   = Address::create(['street' => 'Limmatstrasse',           'number' => '152', 'postal_code' => '8005', 'city' => 'Zurich']);
        $addressSwisscom = Address::create(['street' => 'Alte Tiefenaustrasse',    'number' => '6',   'postal_code' => '3050', 'city' => 'Berne']);
        $addressLaPoste  = Address::create(['street' => 'Wankdorfallee',           'number' => '4',   'postal_code' => '3030', 'city' => 'Berne']);
        $addressSwatch   = Address::create(['street' => 'Nicolas G. Hayek',        'number' => '1',   'postal_code' => '2502', 'city' => 'Bienne']);
        $addressLaTour   = Address::create(['street' => 'Avenue J.-D. Maillard',   'number' => '3',   'postal_code' => '1217', 'city' => 'Meyrin']);
        $addressSig      = Address::create(['street' => 'Chemin du Château-Bloch', 'number' => '2',   'postal_code' => '1219', 'city' => 'Le Lignon']);

        // ===== COMPANIES (10) =====
        $ubs      = Company::create(['name' => 'UBS',                'address_id' => $addressUbs->id,      'nb_employee' => 5000]);
        $coop     = Company::create(['name' => 'Coop',               'address_id' => $addressCoop->id,     'nb_employee' => 8000]);
        $nestle   = Company::create(['name' => 'Nestlé',             'address_id' => $addressNestle->id,   'nb_employee' => 3000]);
        $rolex    = Company::create(['name' => 'Rolex',              'address_id' => $addressRolex->id,    'nb_employee' => 800]);
        $migros   = Company::create(['name' => 'Migros',             'address_id' => $addressMigros->id,   'nb_employee' => 6000]);
        $swisscom = Company::create(['name' => 'Swisscom',           'address_id' => $addressSwisscom->id, 'nb_employee' => 4500]);
        $laPoste  = Company::create(['name' => 'La Poste',           'address_id' => $addressLaPoste->id,  'nb_employee' => 3500]);
        $swatch   = Company::create(['name' => 'Swatch',             'address_id' => $addressSwatch->id,   'nb_employee' => 900]);
        $laTour   = Company::create(['name' => 'Hôpital de La Tour', 'address_id' => $addressLaTour->id,   'nb_employee' => 150]);
        $sig      = Company::create(['name' => 'SIG',                'address_id' => $addressSig->id,      'nb_employee' => 45]);

        // ===== CONTACTS =====
        Contact::create(['company_id' => $ubs->id,      'email' => 'contact@ubs.ch',      'phone' => '+41 44 234 85 00']);
        Contact::create(['company_id' => $coop->id,     'email' => 'contact@coop.ch',     'phone' => '+41 44 724 72 47']);
        Contact::create(['company_id' => $nestle->id,   'email' => 'contact@nestle.ch',   'phone' => '+41 21 924 24 24']);
        Contact::create(['company_id' => $rolex->id,    'email' => 'contact@rolex.ch',    'phone' => '+41 22 302 22 00']);
        Contact::create(['company_id' => $migros->id,   'email' => 'contact@migros.ch',   'phone' => '+41 58 570 00 00']);
        Contact::create(['company_id' => $swisscom->id, 'email' => 'contact@swisscom.ch', 'phone' => '+41 58 221 21 11']);
        Contact::create(['company_id' => $laPoste->id,  'email' => 'contact@poste.ch',    'phone' => '+41 58 338 00 00']);
        Contact::create(['company_id' => $swatch->id,   'email' => 'contact@swatch.ch',   'phone' => '+41 32 343 90 00']);
        Contact::create(['company_id' => $laTour->id,   'email' => 'contact@latour.ch',   'phone' => '+41 22 719 61 11']);
        Contact::create(['company_id' => $sig->id,      'email' => 'contact@sig-ge.ch',   'phone' => '+41 22 420 80 00']);

        // ===== COLLECTIONS =====

        // UBS (2021, 2023, 2024, 2025 terminées + 2026 en cours)
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@ubs.ch', 'contact_phone' => '+41 44 234 85 00', 'venue_street' => $addressUbs->street, 'venue_number' => $addressUbs->number, 'venue_postal_code' => $addressUbs->postal_code, 'venue_city' => $addressUbs->city, 'start_date' => '2021-02-01', 'end_date' => '2021-02-28', 'capacity' => 60,  'primary_color' => '#EB001B', 'logo_url' => '/images/logos/logo-ubs.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2021', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/a7dc3241-a05d-4b91-9f74-5485487a2fee', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@ubs.ch', 'contact_phone' => '+41 44 234 85 00', 'venue_street' => $addressUbs->street, 'venue_number' => $addressUbs->number, 'venue_postal_code' => $addressUbs->postal_code, 'venue_city' => $addressUbs->city, 'start_date' => '2023-02-01', 'end_date' => '2023-02-28', 'capacity' => 60,  'primary_color' => '#EB001B', 'logo_url' => '/images/logos/logo-ubs.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2023', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/00ca8b8b-6f4f-4deb-89a9-4ce248612e41', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@ubs.ch', 'contact_phone' => '+41 44 234 85 00', 'venue_street' => $addressUbs->street, 'venue_number' => $addressUbs->number, 'venue_postal_code' => $addressUbs->postal_code, 'venue_city' => $addressUbs->city, 'start_date' => '2024-03-01', 'end_date' => '2024-03-31', 'capacity' => 75,  'primary_color' => '#EB001B', 'logo_url' => '/images/logos/logo-ubs.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2024', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/95153db1-1e80-4213-8d3f-2dad7e332023', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@ubs.ch', 'contact_phone' => '+41 44 234 85 00', 'venue_street' => $addressUbs->street, 'venue_number' => $addressUbs->number, 'venue_postal_code' => $addressUbs->postal_code, 'venue_city' => $addressUbs->city, 'start_date' => '2025-04-01', 'end_date' => '2025-04-30', 'capacity' => 75,  'primary_color' => '#EB001B', 'logo_url' => '/images/logos/logo-ubs.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2025', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/87a8bb1e-516e-4219-abcb-174bfd5a3849', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $ubs->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@ubs.ch', 'contact_phone' => '+41 44 234 85 00', 'venue_street' => $addressUbs->street, 'venue_number' => $addressUbs->number, 'venue_postal_code' => $addressUbs->postal_code, 'venue_city' => $addressUbs->city, 'start_date' => '2026-05-01', 'end_date' => '2026-06-30', 'capacity' => 80,  'primary_color' => '#EB001B', 'logo_url' => '/images/logos/logo-ubs.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/500fd4b7-07cb-4f1a-9cc2-22108a3bd646', 'public_token' => Str::random(32)]);

        // Coop (2022, 2023, 2024, 2025 terminées + 2026 en cours)
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@coop.ch', 'contact_phone' => '+41 44 724 72 47', 'venue_street' => $addressCoop->street, 'venue_number' => $addressCoop->number, 'venue_postal_code' => $addressCoop->postal_code, 'venue_city' => $addressCoop->city, 'start_date' => '2022-05-01', 'end_date' => '2022-05-31', 'capacity' => 50,  'primary_color' => '#FF6B35', 'logo_url' => '/images/logos/logo-coop.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2022', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/bd2680b9-6b95-4f7a-a85f-07e5fd8bc923', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@coop.ch', 'contact_phone' => '+41 44 724 72 47', 'venue_street' => $addressCoop->street, 'venue_number' => $addressCoop->number, 'venue_postal_code' => $addressCoop->postal_code, 'venue_city' => $addressCoop->city, 'start_date' => '2023-05-01', 'end_date' => '2023-05-31', 'capacity' => 50,  'primary_color' => '#FF6B35', 'logo_url' => '/images/logos/logo-coop.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2023', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/7986b119-95f3-4c94-b30d-de18e472bfbf', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@coop.ch', 'contact_phone' => '+41 44 724 72 47', 'venue_street' => $addressCoop->street, 'venue_number' => $addressCoop->number, 'venue_postal_code' => $addressCoop->postal_code, 'venue_city' => $addressCoop->city, 'start_date' => '2024-06-01', 'end_date' => '2024-06-30', 'capacity' => 55,  'primary_color' => '#FF6B35', 'logo_url' => '/images/logos/logo-coop.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2024', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/f64a6dce-7161-4286-aa8c-4733b08752d8', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@coop.ch', 'contact_phone' => '+41 44 724 72 47', 'venue_street' => $addressCoop->street, 'venue_number' => $addressCoop->number, 'venue_postal_code' => $addressCoop->postal_code, 'venue_city' => $addressCoop->city, 'start_date' => '2025-07-01', 'end_date' => '2025-08-15', 'capacity' => 55,  'primary_color' => '#FF6B35', 'logo_url' => '/images/logos/logo-coop.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2025', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/6e6a8422-d41c-48aa-9367-992d24c50a35', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $coop->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@coop.ch', 'contact_phone' => '+41 44 724 72 47', 'venue_street' => $addressCoop->street, 'venue_number' => $addressCoop->number, 'venue_postal_code' => $addressCoop->postal_code, 'venue_city' => $addressCoop->city, 'start_date' => '2026-06-01', 'end_date' => '2026-07-15', 'capacity' => 60,  'primary_color' => '#FF6B35', 'logo_url' => '/images/logos/logo-coop.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/1e26b251-02df-4c83-869b-f8f70fe3d121', 'public_token' => Str::random(32)]);

        // Nestlé (2021, 2022, 2023 terminées + 2026 en cours)
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@nestle.ch', 'contact_phone' => '+41 21 924 24 24', 'venue_street' => $addressNestle->street, 'venue_number' => $addressNestle->number, 'venue_postal_code' => $addressNestle->postal_code, 'venue_city' => $addressNestle->city, 'start_date' => '2021-05-15', 'end_date' => '2021-05-31', 'capacity' => 40, 'primary_color' => '#6B4423', 'logo_url' => '/images/logos/logo-nestle.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2021', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/6626722d-4d74-4aaf-8a81-bfa213d530f7', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@nestle.ch', 'contact_phone' => '+41 21 924 24 24', 'venue_street' => $addressNestle->street, 'venue_number' => $addressNestle->number, 'venue_postal_code' => $addressNestle->postal_code, 'venue_city' => $addressNestle->city, 'start_date' => '2022-03-15', 'end_date' => '2022-03-31', 'capacity' => 40, 'primary_color' => '#6B4423', 'logo_url' => '/images/logos/logo-nestle.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2022', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/21d55d3b-9fb2-4c56-b7c8-1f63418c112e', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@nestle.ch', 'contact_phone' => '+41 21 924 24 24', 'venue_street' => $addressNestle->street, 'venue_number' => $addressNestle->number, 'venue_postal_code' => $addressNestle->postal_code, 'venue_city' => $addressNestle->city, 'start_date' => '2023-03-15', 'end_date' => '2023-03-31', 'capacity' => 45, 'primary_color' => '#6B4423', 'logo_url' => '/images/logos/logo-nestle.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2023', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/c798e197-2251-4409-9ba3-367b7c767c79', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $nestle->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@nestle.ch', 'contact_phone' => '+41 21 924 24 24', 'venue_street' => $addressNestle->street, 'venue_number' => $addressNestle->number, 'venue_postal_code' => $addressNestle->postal_code, 'venue_city' => $addressNestle->city, 'start_date' => '2026-05-15', 'end_date' => '2026-07-15', 'capacity' => 50, 'primary_color' => '#6B4423', 'logo_url' => '/images/logos/logo-nestle.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/nestle-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/c18375c9-8c99-418a-a51b-019a43e2dedb', 'public_token' => Str::random(32)]);

        // Rolex (2021, 2022, 2023, 2024, 2025 terminées)
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@rolex.ch', 'contact_phone' => '+41 22 302 22 00', 'venue_street' => $addressRolex->street, 'venue_number' => $addressRolex->number, 'venue_postal_code' => $addressRolex->postal_code, 'venue_city' => $addressRolex->city, 'start_date' => '2021-04-01', 'end_date' => '2021-04-30', 'capacity' => 30, 'primary_color' => '#000000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2021', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/c15278f3-8819-4560-8323-c1d8603524e6', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@rolex.ch', 'contact_phone' => '+41 22 302 22 00', 'venue_street' => $addressRolex->street, 'venue_number' => $addressRolex->number, 'venue_postal_code' => $addressRolex->postal_code, 'venue_city' => $addressRolex->city, 'start_date' => '2022-06-01', 'end_date' => '2022-06-30', 'capacity' => 30, 'primary_color' => '#000000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2022', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/a09b54df-8d83-4cc5-9401-ea9b47e33c7b', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@rolex.ch', 'contact_phone' => '+41 22 302 22 00', 'venue_street' => $addressRolex->street, 'venue_number' => $addressRolex->number, 'venue_postal_code' => $addressRolex->postal_code, 'venue_city' => $addressRolex->city, 'start_date' => '2023-07-01', 'end_date' => '2023-07-31', 'capacity' => 35, 'primary_color' => '#000000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2023', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/2123ce1f-c146-4e49-bafd-909498c899ef', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@rolex.ch', 'contact_phone' => '+41 22 302 22 00', 'venue_street' => $addressRolex->street, 'venue_number' => $addressRolex->number, 'venue_postal_code' => $addressRolex->postal_code, 'venue_city' => $addressRolex->city, 'start_date' => '2024-05-01', 'end_date' => '2024-05-31', 'capacity' => 35, 'primary_color' => '#000000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2024', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/b7c4e2a1-3f19-4d8a-a612-8e9f0c7d5b3a', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $rolex->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@rolex.ch', 'contact_phone' => '+41 22 302 22 00', 'venue_street' => $addressRolex->street, 'venue_number' => $addressRolex->number, 'venue_postal_code' => $addressRolex->postal_code, 'venue_city' => $addressRolex->city, 'start_date' => '2025-08-01', 'end_date' => '2025-08-31', 'capacity' => 35, 'primary_color' => '#000000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2025', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/ac77756b-37af-4150-b9b8-819966c48d97', 'public_token' => Str::random(32)]);

        // Migros (2025, 2026)
        Collection::create(['company_id' => $migros->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@migros.ch', 'contact_phone' => '+41 58 570 00 00', 'venue_street' => $addressMigros->street, 'venue_number' => $addressMigros->number, 'venue_postal_code' => $addressMigros->postal_code, 'venue_city' => $addressMigros->city, 'start_date' => '2025-09-01', 'end_date' => '2025-09-30', 'capacity' => 100, 'primary_color' => '#FF6600', 'logo_url' => '/images/logos/logo-migros.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/migros-2025', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/47e63cbc-b0aa-4603-a72b-56f8c13c02d2', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $migros->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@migros.ch', 'contact_phone' => '+41 58 570 00 00', 'venue_street' => $addressMigros->street, 'venue_number' => $addressMigros->number, 'venue_postal_code' => $addressMigros->postal_code, 'venue_city' => $addressMigros->city, 'start_date' => '2026-04-01', 'end_date' => '2026-05-15', 'capacity' => 100, 'primary_color' => '#FF6600', 'logo_url' => '/images/logos/logo-migros.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/migros-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/5e375a9f-1e30-4e49-af22-d9fe3c84318c', 'public_token' => Str::random(32)]);

        // Swisscom (2024, 2026 en cours)
        Collection::create(['company_id' => $swisscom->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@swisscom.ch', 'contact_phone' => '+41 58 221 21 11', 'venue_street' => $addressSwisscom->street, 'venue_number' => $addressSwisscom->number, 'venue_postal_code' => $addressSwisscom->postal_code, 'venue_city' => $addressSwisscom->city, 'start_date' => '2024-10-01', 'end_date' => '2024-10-31', 'capacity' => 90, 'primary_color' => '#0066CC', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/swisscom-2024', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/04bca390-4c37-459a-9453-1220e0bae7c9', 'public_token' => Str::random(32)]);
        Collection::create(['company_id' => $swisscom->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@swisscom.ch', 'contact_phone' => '+41 58 221 21 11', 'venue_street' => $addressSwisscom->street, 'venue_number' => $addressSwisscom->number, 'venue_postal_code' => $addressSwisscom->postal_code, 'venue_city' => $addressSwisscom->city, 'start_date' => '2026-05-01', 'end_date' => '2026-06-30', 'capacity' => 90, 'primary_color' => '#0066CC', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/swisscom-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/75672673-acd9-4c51-9ba1-9ed45c4ebacb', 'public_token' => Str::random(32)]);

        // La Poste (2025)
        Collection::create(['company_id' => $laPoste->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@poste.ch', 'contact_phone' => '+41 58 338 00 00', 'venue_street' => $addressLaPoste->street, 'venue_number' => $addressLaPoste->number, 'venue_postal_code' => $addressLaPoste->postal_code, 'venue_city' => $addressLaPoste->city, 'start_date' => '2025-01-15', 'end_date' => '2025-02-15', 'capacity' => 70, 'primary_color' => '#FFCC00', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/poste-2025', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/690b86ff-5e07-472e-8355-3fbca962a0c4', 'public_token' => Str::random(32)]);

        // Swatch (2026)
        Collection::create(['company_id' => $swatch->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@swatch.ch', 'contact_phone' => '+41 32 343 90 00', 'venue_street' => $addressSwatch->street, 'venue_number' => $addressSwatch->number, 'venue_postal_code' => $addressSwatch->postal_code, 'venue_city' => $addressSwatch->city, 'start_date' => '2026-03-01', 'end_date' => '2026-03-31', 'capacity' => 45, 'primary_color' => '#FF0000', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/swatch-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/35369345-98fe-44da-b615-798814b4e0f4', 'public_token' => Str::random(32)]);

        // Hôpital de La Tour (2024)
        Collection::create(['company_id' => $laTour->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@latour.ch', 'contact_phone' => '+41 22 719 61 11', 'venue_street' => $addressLaTour->street, 'venue_number' => $addressLaTour->number, 'venue_postal_code' => $addressLaTour->postal_code, 'venue_city' => $addressLaTour->city, 'start_date' => '2024-04-01', 'end_date' => '2024-04-30', 'capacity' => 25, 'primary_color' => '#0099CC', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/latour-2024', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/635f9409-1c8d-43e9-9662-c58bd855b5c9', 'public_token' => Str::random(32)]);

        // SIG (2026)
        Collection::create(['company_id' => $sig->id, 'user_id' => $adminUser->id, 'contact_email' => 'contact@sig-ge.ch', 'contact_phone' => '+41 22 420 80 00', 'venue_street' => $addressSig->street, 'venue_number' => $addressSig->number, 'venue_postal_code' => $addressSig->postal_code, 'venue_city' => $addressSig->city, 'start_date' => '2026-02-01', 'end_date' => '2026-02-28', 'capacity' => 35, 'primary_color' => '#339966', 'logo_url' => '/images/logos/logo-hug.png', 'onedoc_url' => 'https://onedoc.hug.ch/collections/sig-2026', 'kit_url' => 'https://kdrive.infomaniak.com/app/share/3148979/d6f5b6ad-338f-45aa-80f1-e7e00e1127c2', 'public_token' => Str::random(32)]);

        // ===== LABELS =====
        // Le label CTS est unique ; l'historique de chaque entreprise est dérivé
        // automatiquement de ses collectes (fenêtre glissante de 2 ans à partir de
        // la date de fin de chaque collecte). Voir App\Services\LabelService.
        $labelService = new LabelService();
        foreach ([$ubs, $coop, $nestle, $rolex, $migros, $swisscom, $laPoste, $swatch, $laTour, $sig] as $entreprise) {
            $labelService->synchronise($entreprise);
        }

        // ===== TROPHEES =====
        $tropheeOr2021 = Trophee::create(['name' => 'Trophée Or 2021', 'year' => 2021]);
        $rolex->trophees()->attach($tropheeOr2021->id, ['rank' => 1]);
        $tropheeArgent2021 = Trophee::create(['name' => 'Trophée Argent 2021', 'year' => 2021]);
        $nestle->trophees()->attach($tropheeArgent2021->id, ['rank' => 2]);
        $tropheeBronze2021 = Trophee::create(['name' => 'Trophée Bronze 2021', 'year' => 2021]);
        $ubs->trophees()->attach($tropheeBronze2021->id, ['rank' => 3]);

        $tropheeOr2022 = Trophee::create(['name' => 'Trophée Or 2022', 'year' => 2022]);
        $coop->trophees()->attach($tropheeOr2022->id, ['rank' => 1]);
        $tropheeArgent2022 = Trophee::create(['name' => 'Trophée Argent 2022', 'year' => 2022]);
        $rolex->trophees()->attach($tropheeArgent2022->id, ['rank' => 2]);
        $tropheeBronze2022 = Trophee::create(['name' => 'Trophée Bronze 2022', 'year' => 2022]);
        $nestle->trophees()->attach($tropheeBronze2022->id, ['rank' => 3]);

        $tropheeOr2023 = Trophee::create(['name' => 'Trophée Or 2023', 'year' => 2023]);
        $nestle->trophees()->attach($tropheeOr2023->id, ['rank' => 1]);
        $tropheeArgent2023 = Trophee::create(['name' => 'Trophée Argent 2023', 'year' => 2023]);
        $ubs->trophees()->attach($tropheeArgent2023->id, ['rank' => 2]);
        $tropheeBronze2023 = Trophee::create(['name' => 'Trophée Bronze 2023', 'year' => 2023]);
        $coop->trophees()->attach($tropheeBronze2023->id, ['rank' => 3]);

        $tropheeOr2024 = Trophee::create(['name' => 'Trophée Or 2024', 'year' => 2024]);
        $ubs->trophees()->attach($tropheeOr2024->id, ['rank' => 1]);
        $tropheeArgent2024 = Trophee::create(['name' => 'Trophée Argent 2024', 'year' => 2024]);
        $coop->trophees()->attach($tropheeArgent2024->id, ['rank' => 2]);
        $tropheeBronze2024 = Trophee::create(['name' => 'Trophée Bronze 2024', 'year' => 2024]);
        $rolex->trophees()->attach($tropheeBronze2024->id, ['rank' => 3]);

        $tropheeOr2025 = Trophee::create(['name' => 'Trophée Or 2025', 'year' => 2025]);
        $ubs->trophees()->attach($tropheeOr2025->id, ['rank' => 1]);
        $tropheeArgent2025 = Trophee::create(['name' => 'Trophée Argent 2025', 'year' => 2025]);
        $coop->trophees()->attach($tropheeArgent2025->id, ['rank' => 2]);
        $tropheeBronze2025 = Trophee::create(['name' => 'Trophée Bronze 2025', 'year' => 2025]);
        $rolex->trophees()->attach($tropheeBronze2025->id, ['rank' => 3]);

        $tropheeOr2026 = Trophee::create(['name' => 'Trophée Or 2026', 'year' => 2026]);
        $nestle->trophees()->attach($tropheeOr2026->id, ['rank' => 1]);
        $tropheeArgent2026 = Trophee::create(['name' => 'Trophée Argent 2026', 'year' => 2026]);
        $ubs->trophees()->attach($tropheeArgent2026->id, ['rank' => 2]);
        $tropheeBronze2026 = Trophee::create(['name' => 'Trophée Bronze 2026', 'year' => 2026]);
        $coop->trophees()->attach($tropheeBronze2026->id, ['rank' => 3]);

        // ===== ÉVÉNEMENTS DE TRACKING (KPI analytics) =====
        $this->call([
            QuizEventSeeder::class,
            PageEventSeeder::class,
        ]);
    }
}
