<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class';

    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Services/{$name}.php");

        if (file_exists($path)) {
            $this->error('Service already exists!');
            return;
        }

        (new Filesystem)->ensureDirectoryExists(app_path('Services'));

        file_put_contents($path, "<?php\n\nnamespace App\Services;\n\nclass {$name} \n{\n    // Service logic here\n}\n");

        $this->info("Service {$name} created successfully.");
    }
}
