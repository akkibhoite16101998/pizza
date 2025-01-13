<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateFolder extends Command
{
    protected $signature = 'make:folder {folder_name}';
    protected $description = 'Create a new folder in the public path';

    public function handle()
    {
        $folderName = $this->argument('folder_name');
        $path = public_path($folderName);  // public folder में फोल्डर बनाने के लिए

        if (!File::exists($path)) {
            File::makeDirectory($path, 0777, true);
            $this->info("Folder '$folderName' created successfully in public folder!");
        } else {
            $this->error("Folder '$folderName' already exists.");
        }
    }
}
