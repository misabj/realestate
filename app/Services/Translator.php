<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Exceptions\RateLimitException;

class Translator
{
    /**
     * Vraća JSON: ['title' => ['en','sr','ru'], 'description' => ['en','sr','ru']]
     * Ako API padne (429 ili bilo šta), baca izuzetak — queue job će to retry-ovati.
     */


    public function translateBatch(array $payload): array
    {
        $model = env('TRANSLATOR_MODEL', 'gpt-4o-mini');

        $system = 'Return ONLY valid JSON. No prose.
                {
                "title": { "en": string, "sr": string, "ru": string },
                "description": { "en": string, "sr": string, "ru": string }
                }';

                        $user = 'Translate the given fields into Serbian (sr) and Russian (ru), and also return English (en).
                Always fill all three languages (en, sr, ru). No emojis. Keep real-estate tone.
                Input JSON: ' . json_encode([
            'title' => (string)($payload['title'] ?? ''),
            'description' => (string)($payload['description'] ?? ''),
        ], JSON_UNESCAPED_UNICODE);

        // Jedan pokušaj – retry prepuštamo job-u
        $resp = OpenAI::chat()->create([
            'model'       => $model,
            'temperature' => 0,
            'response_format' => ['type' => 'json_object'],
            'messages'    => [
                [
                    'role' => 'system',
                    'content' =>
                    'You are a translation engine. Output ONLY valid JSON (UTF-8), exactly this schema:
            {
            "title": { "en": string, "sr": string, "ru": string },
            "description": { "en": string, "sr": string, "ru": string }
            }
            Rules:
            - Always fill "sr" (Serbian Latin, sr-Latn) and "ru".
            - Keep meaning, neutral real-estate tone, no emojis.
            - If source is English, still translate to Serbian (Latin) & Russian; do NOT leave blank.'
                ],
                ['role' => 'user',   'content' => $user],
            ],
        ]);

        $content = $resp->choices[0]->message->content ?? '';
        $json = json_decode($content, true);

        if (!is_array($json)) {
            throw new \RuntimeException('Translator: invalid JSON');
        }

        foreach (['title', 'description'] as $k) {
            $json[$k] = is_array($json[$k] ?? null) ? $json[$k] : [];
            foreach (['en', 'sr', 'ru'] as $lng) {
                $val = $json[$k][$lng] ?? '';
                if (!is_string($val)) $val = '';
                $json[$k][$lng] = $val;
            }
        }

        // Ako je sr/ru prazno – tretiramo kao neuspeh (prepusti queue retry-u)
        $missing = empty(trim($json['title']['sr'])) || empty(trim($json['title']['ru']))
            || empty(trim($json['description']['sr'])) || empty(trim($json['description']['ru']));
        if ($missing) {
            throw new \RuntimeException('Translator: SR/RU empty in response');
        }

        return $json;
    }
}
