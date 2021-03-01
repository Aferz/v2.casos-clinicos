<?php

namespace Database\Seeders;

use App\Models\User;
use Closure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    protected Collection $dontRemovePaths;

    public function run()
    {
        $this->dontRemovePaths = User::query()
            ->pluck('avatar_path')
            ->map(fn (string $path) => "images/users/$path")
            ->toBase();

        $this->removeMedia();

        $this->call(ProductionSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(ClinicalCasesSeeder::class);
        $this->call(EvaluationsSeeder::class);
    }

    protected function removeMedia()
    {
        collect(Storage::drive('public')->files('images/users'))->map(Closure::fromCallable([$this, 'unlink']));
        collect(Storage::drive('public')->files('images/clinical-cases'))->map(Closure::fromCallable([$this, 'unlink']));
        collect(Storage::drive('public')->files('videos/clinical-cases'))->map(Closure::fromCallable([$this, 'unlink']));
    }

    protected function unlink(string $file): void
    {
        if (
            Str::endsWith($file, '.gitkeep') === false
            && $this->dontRemovePaths->contains($file) === false
        ) {
            unlink(Storage::drive('public')->path($file));
        }
    }
}
