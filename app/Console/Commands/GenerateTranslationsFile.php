<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateTranslationsFile extends Command
{
    protected $signature = 'translations:generate
        {locale=en : The locale to generate translations}
        {--empty : Doesn\'t copy the key of the translation into the value.}
    ';

    protected $description = 'Generates the JSON translations file';

    public function handle(): int
    {
        $locale = $this->argument('locale');
        $translations = $this->getTranslationsInSourceCode();
        $existing = $this->getExistingTranslations($locale);
        $result = array_merge($translations, $existing);

        ksort($result);
        $contents = json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        file_put_contents(resource_path("lang/$locale.json"), $contents);

        return 0;
    }

    protected function getExistingTranslations(string $locale): array
    {
        $path = resource_path("lang/$locale.json");

        if (! file_exists($path)) {
            return [];
        }

        return json_decode(file_get_contents($path), true);
    }

    protected function getTranslationsInSourceCode(): array
    {
        $pattern = '[^\w](?<!->)(__|trans_choice)\([\n\r]?[\s]*?[\'"](.+)[\'"][\),]';

        $globs = [
            base_path('app/*.php'),
            base_path('app/*/*.php'),
            base_path('app/*/*/*.php'),
            base_path('app/*/*/*/*.php'),
            base_path('database/*.php'),
            base_path('database/*/*.php'),
            base_path('database/*/*/*.php'),
            base_path('database/*/*/*/*.php'),
            base_path('resources/views/*.php'),
            base_path('resources/views/*/*.php'),
            base_path('resources/views/*/*/*.php'),
            base_path('resources/views/*/*/*/*.php'),
        ];

        $allMatches = [];

        foreach ($files = glob('{' . implode(',', $globs) . '}', GLOB_BRACE) as $file) {
            if (preg_match_all("/$pattern/siU", file_get_contents($file), $matches)) {
                foreach ($matches[2] as $match) {
                    $match = str_replace("\'", '\'', $match);

                    $allMatches[$match] = $this->option('empty') ? '' : $match;
                }
            }
        }

        return $allMatches;
    }
}
