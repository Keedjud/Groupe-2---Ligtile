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
        $addressUbs = Address::create([
            'street' => 'Rue de la Banque',
            'number' => '1',
            'postal_code' => '1200',
            'city' => 'Genève',
        ]);

        $addressCoop = Address::create([
            'street' => 'Avenue Coop',
            'number' => '50',
            'postal_code' => '8050',
            'city' => 'Zurich',
        ]);

        $addressNestlé = Address::create([
            'street' => 'Route Nestlé',
            'number' => '77',
            'postal_code' => '1800',
            'city' => 'Vevey',
        ]);

        $addressRolex = Address::create([
            'street' => 'Rue de la Manufacture',
            'number' => '3',
            'postal_code' => '1204',
            'city' => 'Genève',
        ]);

        // ===== COMPANIES =====
        $ubs = Company::create([
            'name' => 'UBS',
            'address_id' => $addressUbs->id,
            'email' => 'contact@ubs.ch',
            'phone_number' => '+41 44 234 85 00',
            'nb_employee' => 5000,
        ]);

        $coop = Company::create([
            'name' => 'Coop',
            'address_id' => $addressCoop->id,
            'email' => 'contact@coop.ch',
            'phone_number' => '+41 44 724 72 47',
            'nb_employee' => 8000,
        ]);

        $nestlé = Company::create([
            'name' => 'Nestlé',
            'address_id' => $addressNestlé->id,
            'email' => 'contact@nestlé.ch',
            'phone_number' => '+41 21 924 24 24',
            'nb_employee' => 3000,
        ]);

        $rolex = Company::create([
            'name' => 'Rolex',
            'address_id' => $addressRolex->id,
            'email' => 'contact@rolex.ch',
            'phone_number' => '+41 22 302 22 00',
            'nb_employee' => 800,
        ]);

        // ===== COLLECTIONS =====

        // UBS : 2023, 2024, 2025 (terminées) + 2026 (EN COURS)
        Collection::create([
            'company_id' => $ubs->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressUbs->id,
            'start_date' => '2023-02-01',
            'end_date' => '2023-02-28',
            'primary_color' => '#EB001B',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/ubs.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2023',
            'public_token' => Str::random(32),
            'nb_registered' => 250,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressUbs->id,
            'start_date' => '2024-03-01',
            'end_date' => '2024-03-31',
            'primary_color' => '#EB001B',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/ubs.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2024',
            'public_token' => Str::random(32),
            'nb_registered' => 300,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressUbs->id,
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-30',
            'primary_color' => '#EB001B',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/ubs.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2025',
            'public_token' => Str::random(32),
            'nb_registered' => 320,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressUbs->id,
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-30',
            'primary_color' => '#EB001B',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/ubs.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/ubs-2026',
            'public_token' => Str::random(32),
            'nb_registered' => 150,
        ]);

        // Coop : 2023, 2024, 2025 (terminées)
        Collection::create([
            'company_id' => $coop->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressCoop->id,
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-31',
            'primary_color' => '#FF6B35',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/coop.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2023',
            'public_token' => Str::random(32),
            'nb_registered' => 180,
        ]);

        Collection::create([
            'company_id' => $coop->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressCoop->id,
            'start_date' => '2024-06-01',
            'end_date' => '2024-06-30',
            'primary_color' => '#FF6B35',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/coop.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2024',
            'public_token' => Str::random(32),
            'nb_registered' => 200,
        ]);

        Collection::create([
            'company_id' => $coop->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressCoop->id,
            'start_date' => '2025-07-01',
            'end_date' => '2025-08-15',
            'primary_color' => '#FF6B35',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/coop.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/coop-2025',
            'public_token' => Str::random(32),
            'nb_registered' => 220,
        ]);

        // Nestlé : 2023 + 2026 (EN COURS)
        Collection::create([
            'company_id' => $nestlé->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressNestlé->id,
            'start_date' => '2023-03-15',
            'end_date' => '2023-03-31',
            'primary_color' => '#6B4423',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/nestlé.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/nestlé-2023',
            'public_token' => Str::random(32),
            'nb_registered' => 350,
        ]);

        Collection::create([
            'company_id' => $nestlé->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressNestlé->id,
            'start_date' => '2026-05-15',
            'end_date' => '2026-07-15',
            'primary_color' => '#6B4423',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/nestlé.ch',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/nestlé-2026',
            'public_token' => Str::random(32),
            'nb_registered' => 180,
        ]);

        // Rolex : 2023
        Collection::create([
            'company_id' => $rolex->id,
            'user_id' => $adminUser->id,
            'address_id' => $addressRolex->id,
            'start_date' => '2023-07-01',
            'end_date' => '2023-07-31',
            'primary_color' => '#000000',
            'secondary_color' => '#c44444',
            'logo_url' => 'https://logo.clearbit.com/rolex.com',
            'onedoc_url' => 'https://onedoc.hug.ch/collections/rolex-2023',
            'public_token' => Str::random(32),
            'nb_registered' => 140,
        ]);

        // ===== LABELS =====
        // Un label par année

        // 2023
        $label2023 = Label::create(['name' => 'Label CTS 2023']);
        $ubs->labels()->attach($label2023->id, [
            'start_date' => '2023-02-01',
            'end_date' => '2025-02-01',
        ]);
        $coop->labels()->attach($label2023->id, [
            'start_date' => '2023-05-01',
            'end_date' => '2025-05-01',
        ]);
        $nestlé->labels()->attach($label2023->id, [
            'start_date' => '2023-03-15',
            'end_date' => '2025-03-15',
        ]);
        $rolex->labels()->attach($label2023->id, [
            'start_date' => '2023-07-01',
            'end_date' => '2025-07-01',
        ]);

        // 2024
        $label2024 = Label::create(['name' => 'Label CTS 2024']);
        $ubs->labels()->attach($label2024->id, [
            'start_date' => '2024-03-01',
            'end_date' => '2026-03-01',
        ]);
        $coop->labels()->attach($label2024->id, [
            'start_date' => '2024-06-01',
            'end_date' => '2026-06-01',
        ]);

        // 2025
        $label2025 = Label::create(['name' => 'Label CTS 2025']);
        $ubs->labels()->attach($label2025->id, [
            'start_date' => '2025-04-01',
            'end_date' => '2027-04-01',
        ]);
        $coop->labels()->attach($label2025->id, [
            'start_date' => '2025-07-01',
            'end_date' => '2027-07-01',
        ]);

        // 2026
        $label2026 = Label::create(['name' => 'Label CTS 2026']);
        $ubs->labels()->attach($label2026->id, [
            'start_date' => '2026-05-01',
            'end_date' => '2028-05-01',
        ]);
        $nestlé->labels()->attach($label2026->id, [
            'start_date' => '2026-05-15',
            'end_date' => '2028-05-15',
        ]);

        // ===== TROPHEES =====
        // 3 types de trophée par année : Or, Argent, Bronze

        // 2023
        $tropheeOr2023 = Trophee::create(['name' => 'Trophée Or 2023', 'year' => 2023]);
        $nestlé->trophees()->attach($tropheeOr2023->id, ['rank' => 1]);

        $tropheeArgent2023 = Trophee::create(['name' => 'Trophée Argent 2023', 'year' => 2023]);
        $ubs->trophees()->attach($tropheeArgent2023->id, ['rank' => 2]);

        $tropheeBronze2023 = Trophee::create(['name' => 'Trophée Bronze 2023', 'year' => 2023]);
        $coop->trophees()->attach($tropheeBronze2023->id, ['rank' => 3]);

        // 2024
        $tropheeOr2024 = Trophee::create(['name' => 'Trophée Or 2024', 'year' => 2024]);
        $ubs->trophees()->attach($tropheeOr2024->id, ['rank' => 1]);

        $tropheeArgent2024 = Trophee::create(['name' => 'Trophée Argent 2024', 'year' => 2024]);
        $coop->trophees()->attach($tropheeArgent2024->id, ['rank' => 2]);

        $tropheeBronze2024 = Trophee::create(['name' => 'Trophée Bronze 2024', 'year' => 2024]);
        $rolex->trophees()->attach($tropheeBronze2024->id, ['rank' => 3]);

        // 2025
        $tropheeOr2025 = Trophee::create(['name' => 'Trophée Or 2025', 'year' => 2025]);
        $ubs->trophees()->attach($tropheeOr2025->id, ['rank' => 1]);

        $tropheeArgent2025 = Trophee::create(['name' => 'Trophée Argent 2025', 'year' => 2025]);
        $coop->trophees()->attach($tropheeArgent2025->id, ['rank' => 2]);

        // 2026
        $tropheeOr2026 = Trophee::create(['name' => 'Trophée Or 2026', 'year' => 2026]);
        $nestlé->trophees()->attach($tropheeOr2026->id, ['rank' => 1]);

        $tropheeArgent2026 = Trophee::create(['name' => 'Trophée Argent 2026', 'year' => 2026]);
        $ubs->trophees()->attach($tropheeArgent2026->id, ['rank' => 2]);
    }
}
