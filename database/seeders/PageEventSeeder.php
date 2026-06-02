<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Données mock page_events. */
class PageEventSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Collection::all() as $collecte) {
            $sessions = random_int(50, 300);
            $when = $collecte->start_date;
            $rows = [];

            for ($i = 0; $i < $sessions; $i++) {
                $sessionId = (string) Str::uuid();
                $engaged   = random_int(0, 100) > 35;

                $rows[] = [
                    'collection_id' => $collecte->id,
                    'session_id'    => $sessionId,
                    'event_type'    => 'prevention_entered',
                    'engaged'       => null,
                    'time_on_page'  => null,
                    'created_at'    => $when,
                ];
                $rows[] = [
                    'collection_id' => $collecte->id,
                    'session_id'    => $sessionId,
                    'event_type'    => 'prevention_exited',
                    'engaged'       => $engaged,
                    'time_on_page'  => $engaged ? random_int(90, 320) : random_int(5, 45),
                    'created_at'    => $when,
                ];
            }

            foreach (array_chunk($rows, 1000) as $chunk) {
                DB::table('page_events')->insert($chunk);
            }
        }
    }
}
