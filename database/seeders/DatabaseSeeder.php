<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Collection;
use App\Models\Label;
use App\Models\Trophy;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== COMPANIES =====
        $ubs = Company::create([
            'name' => 'UBS',
            'slug' => 'ubs',
            'description' => 'UBS AG - Banque suisse',
        ]);

        $coop = Company::create([
            'name' => 'Coop',
            'slug' => 'coop',
            'description' => 'Coop - Chaîne de distribution',
        ]);

        $nestlé = Company::create([
            'name' => 'Nestlé',
            'slug' => 'nestlé',
            'description' => 'Nestlé - Alimentation & nutrition',
        ]);

        $rolex = Company::create([
            'name' => 'Rolex',
            'slug' => 'rolex',
            'description' => 'Rolex - Horlogerie suisse',
        ]);

        // ===== COLLECTIONS =====

        // UBS : 2023, 2024, 2025 (terminées) + 2026 (EN COURS)
        Collection::create([
            'company_id' => $ubs->id,
            'start_date' => '2023-02-01',
            'end_date' => '2023-02-28',
            'blood_units_collected' => 150,
            'registered_count' => 250,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'start_date' => '2024-03-01',
            'end_date' => '2024-03-31',
            'blood_units_collected' => 180,
            'registered_count' => 300,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-30',
            'blood_units_collected' => 200,
            'registered_count' => 320,
        ]);

        Collection::create([
            'company_id' => $ubs->id,
            'start_date' => '2026-05-01',
            'end_date' => '2026-06-30', // EN COURS (aujourd'hui = 2026-06-10)
            'blood_units_collected' => 80,
            'registered_count' => 150,
        ]);

        // Coop : 2023, 2024 (terminées) + 2025 (TERMINÉE)
        Collection::create([
            'company_id' => $coop->id,
            'start_date' => '2023-05-01',
            'end_date' => '2023-05-31',
            'blood_units_collected' => 100,
            'registered_count' => 180,
        ]);

        Collection::create([
            'company_id' => $coop->id,
            'start_date' => '2024-06-01',
            'end_date' => '2024-06-30',
            'blood_units_collected' => 120,
            'registered_count' => 200,
        ]);

        Collection::create([
            'company_id' => $coop->id,
            'start_date' => '2025-07-01',
            'end_date' => '2025-08-15', // Terminée bien avant 2026-06-10
            'blood_units_collected' => 140,
            'registered_count' => 220,
        ]);

        // Nestlé : 2023 (terminée) + 2026 (EN COURS)
        Collection::create([
            'company_id' => $nestlé->id,
            'start_date' => '2023-03-15',
            'end_date' => '2023-03-31',
            'blood_units_collected' => 220,
            'registered_count' => 350,
        ]);

        Collection::create([
            'company_id' => $nestlé->id,
            'start_date' => '2026-05-15',
            'end_date' => '2026-07-15', // EN COURS (aujourd'hui = 2026-06-10)
            'blood_units_collected' => 95,
            'registered_count' => 180,
        ]);

        // Rolex : 2023 uniquement (terminée)
        Collection::create([
            'company_id' => $rolex->id,
            'start_date' => '2023-07-01',
            'end_date' => '2023-07-31',
            'blood_units_collected' => 80,
            'registered_count' => 140,
        ]);

        // ===== LABELS =====

        // UBS
        Label::create([
            'company_id' => $ubs->id,
            'year' => 2023,
            'end_date' => '2025-02-01', // 2 ans après start_date collecte 2023
        ]);

        Label::create([
            'company_id' => $ubs->id,
            'year' => 2024,
            'end_date' => '2026-03-01',
        ]);

        Label::create([
            'company_id' => $ubs->id,
            'year' => 2025,
            'end_date' => '2027-04-01',
        ]);

        Label::create([
            'company_id' => $ubs->id,
            'year' => 2026,
            'end_date' => '2028-05-01', // 2 ans après start_date collecte 2026 (en cours)
        ]);

        // Coop
        Label::create([
            'company_id' => $coop->id,
            'year' => 2023,
            'end_date' => '2025-05-01',
        ]);

        Label::create([
            'company_id' => $coop->id,
            'year' => 2024,
            'end_date' => '2026-06-01',
        ]);

        Label::create([
            'company_id' => $coop->id,
            'year' => 2025,
            'end_date' => '2027-07-01', // 2 ans après start_date collecte 2025
        ]);

        // Nestlé
        Label::create([
            'company_id' => $nestlé->id,
            'year' => 2023,
            'end_date' => '2025-03-15',
        ]);

        Label::create([
            'company_id' => $nestlé->id,
            'year' => 2026,
            'end_date' => '2028-05-15', // 2 ans après start_date collecte 2026 (en cours)
        ]);

        // Rolex
        Label::create([
            'company_id' => $rolex->id,
            'year' => 2023,
            'end_date' => '2025-07-01',
        ]);

        // ===== TROPHIES =====

        // 2023 : Nestlé rank 1 (plus grosse donation), UBS rank 2
        Trophy::create([
            'company_id' => $nestlé->id,
            'year' => 2023,
            'rank' => 1,
        ]);

        Trophy::create([
            'company_id' => $ubs->id,
            'year' => 2023,
            'rank' => 2,
        ]);

        // 2024 : UBS rank 1 (la plus régulière)
        Trophy::create([
            'company_id' => $ubs->id,
            'year' => 2024,
            'rank' => 1,
        ]);

        // 2025 : UBS rank 1 (la plus régulière)
        Trophy::create([
            'company_id' => $ubs->id,
            'year' => 2025,
            'rank' => 1,
        ]);

        // 2026 : Nestlé rank 1 (collecte en cours, belle performance), UBS rank 2
        Trophy::create([
            'company_id' => $nestlé->id,
            'year' => 2026,
            'rank' => 1,
        ]);

        Trophy::create([
            'company_id' => $ubs->id,
            'year' => 2026,
            'rank' => 2,
        ]);
    }
}
