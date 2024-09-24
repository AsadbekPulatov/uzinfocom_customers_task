<?php

namespace App\Jobs;

use App\Events\ExportCustomerEvent;
use App\Models\Customer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ExportCustomerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $fileName = 'customer_export.csv';
        $path = storage_path("app/public/{$fileName}");

        $file = fopen($path, 'w');


        fputcsv($file, ['ID', 'FullName', 'Phone', 'Email', 'Address']);

        Customer::chunk(1000, function ($customers) use ($file) {
            foreach ($customers as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->full_name,
                    $row->phone,
                    $row->email,
                    preg_replace("/\r|\n/", " ", $row->address), // Yangi qator belgilarini olib tashlash
                ]);
            }
        });

        fclose($file);
        event(new ExportCustomerEvent($fileName));
    }
}
