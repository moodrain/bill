<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearDemo extends Command
{
    protected $signature = 'clearDemo {all=0}';
    protected $description = 'clear demo';

    public function handle()
    {
        $files = [
            'app/Http/Controllers/SubjectController.php',
            'app/Http/Controllers/Admin/CommentController.php',
            'app/Http/Controllers/Admin/SubjectCategoryController.php',
            'app/Http/Controllers/Admin/SubjectController.php',
            'app/Models/Comment.php',
            'app/Models/Subject.php',
            'app/Models/Subject',
            'resources/views/admin/subject',
            'resources/views/admin/subject-category',
            'resources/views/admin/comment',
            'resources/views/subject',
        ];
        if ($this->argument('all')) {
            $files = array_merge($files, [
                'app/Http/Controllers/Admin/ExplorerController.php',
                'resources/views/admin/explorer',
            ]);
        }
        foreach($files as $file) {
            $path = base_path($file);
            if (! File::exists($path)) {
                continue;
            }
            File::isDirectory($path) && File::deleteDirectory($path);
            File::isFile($path) && File::delete($path);
        }

    }
}
