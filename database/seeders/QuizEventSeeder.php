<?php

namespace Database\Seeders;

use App\Models\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Données mock quiz_events. */
class QuizEventSeeder extends Seeder
{
    private const QUESTIONS_P1 = ['age', 'poids', 'sante-generale'];
    private const QUESTIONS_P2 = ['medicaments', 'voyages'];

    public function run(): void
    {
        foreach (Collection::all() as $collecte) {
            $started    = random_int(60, 260);
            $eliminated = (int) round($started * random_int(30, 42) / 100);
            $p1Done     = $started - $eliminated;
            $clicks     = (int) round($p1Done * random_int(40, 60) / 100);   // inscrits
            $p2Done     = (int) round($p1Done * random_int(70, 90) / 100);

            // Capacity
            $capacity = (int) round($clicks / (random_int(60, 95) / 100));
            $collecte->update(['capacity' => max($capacity, $clicks, 1)]);

            $when = $collecte->start_date;
            $rows = [];

            $push = function (string $type, ?int $part = null, ?string $slug = null, ?string $result = null) use (&$rows, $collecte, $when) {
                $rows[] = [
                    'collection_id' => $collecte->id,
                    'session_id'    => (string) Str::uuid(),
                    'event_type'    => $type,
                    'part'          => $part,
                    'question_slug' => $slug,
                    'answer_result' => $result,
                    'created_at'    => $when,
                ];
            };

            for ($i = 0; $i < $started; $i++) {
                $push('quiz_started');
            }

            // Partie 1
            foreach (self::QUESTIONS_P1 as $slug) {
                $incorrect = (int) round($started * random_int(10, 35) / 100);
                for ($i = 0; $i < $started - $incorrect; $i++) {
                    $push('question_answered', 1, $slug, 'correct');
                }
                for ($i = 0; $i < $incorrect; $i++) {
                    $push('question_answered', 1, $slug, 'incorrect');
                }
            }
            for ($i = 0; $i < $eliminated; $i++) {
                $push('p1_eliminated', 1);
            }
            for ($i = 0; $i < $p1Done; $i++) {
                $push('p1_completed', 1);
            }

            // Partie 2
            foreach (self::QUESTIONS_P2 as $slug) {
                $skipped  = (int) round($p1Done * random_int(10, 25) / 100);
                $answered = $p1Done - $skipped;
                $incorrect = (int) round($answered * random_int(15, 40) / 100);
                for ($i = 0; $i < $answered - $incorrect; $i++) {
                    $push('question_answered', 2, $slug, 'correct');
                }
                for ($i = 0; $i < $incorrect; $i++) {
                    $push('question_answered', 2, $slug, 'incorrect');
                }
                for ($i = 0; $i < $skipped; $i++) {
                    $push('question_skipped', 2, $slug);
                }
            }
            for ($i = 0; $i < $p2Done; $i++) {
                $push('p2_completed', 2);
                $push('quiz_completed');
            }
            for ($i = 0; $i < $clicks; $i++) {
                $push('onedoc_clicked');
            }

            foreach (array_chunk($rows, 1000) as $chunk) {
                DB::table('quiz_events')->insert($chunk);
            }
        }
    }
}
