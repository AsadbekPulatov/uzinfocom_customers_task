<?php

namespace App\Console\Commands\Elasticsearch;

use App\Models\Customer;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class ExportCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:export-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Customers data export in elasticsearch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $service = new ElasticsearchService();
        $index = "customers";
        $check = $service->exists(['index' => $index])->asBool();
        if (!$check) {
            $service->createIndexes(['index' => $index]);
            $this->info('created elasticsearch index ' . $index);
        } else {
            $service->deleteIndexes(['index' => $index]);
            $service->createIndexes(['index' => $index]);
            $this->info('created elasticsearch index ' . $index);
        }

        Customer::chunk(500, function ($customers) use ($service, $index) {
            foreach ($customers as $customer) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $index,
                    ]
                ];

                $params['body'][] = [
                    'id' => $customer->id,
                    'full_name' => $customer->full_name,
                    'phone' => $customer->phone,
                    'email' => $customer->email,
                    'address' => $customer->address,
                ];
            }
            $response = $service->bulk($params);
            $this->info('elasticsearch bulking process: ');
        });
        $this->info('finished elasticsearch index ' . $index);
    }
}
