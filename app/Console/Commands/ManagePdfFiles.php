<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ManagePdfFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdfs:manage';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'نقل الفواتير الي مجلد invoices وبعد ذلك حذفهم من المشروع';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pdfFolder = public_path(path: 'pdfs');
        $invoicesFolder = 'D:\pdf_invoices';

        if (!File::exists($invoicesFolder)){
            File::makeDirectory(path: $invoicesFolder, mode: 0755 , recursive: true);
        }

        $files = File::files( directory: $pdfFolder);
        foreach ($files as $file){
            $lastModified = Carbon::createFromTimestamp(File::lastModified(path: $file));

            if (Carbon::now()->diffInMinutes($lastModified) >= 1){

                File::move(path: $file->getRealPath(), target: $invoicesFolder . '/' . $file->getFilename());
                $this->info(string: "تم نقل الملف : " . $file->getFilename());
            } else {
                File::delete(paths: $file->getRealPath());
                $this->info("تم حذف الملف : " . $file->getFilename());
            }
        }
        $this->info(string: 'تمت عمليت ادارة ملفات pdf بنجاح');
        return Command::SUCCESS;
    }
}
