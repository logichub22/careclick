<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;

class PushPackageToRiby implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $requirements = [1,2,3,4,5,6];

        $client = new \GuzzleHttp\Client;

        $access_token = 'fe7f0f4f1a0843ea56a8f7a90d8a482ef6c26dee';

        $promise = $client->requestAsync('POST', 'https://testapis.riby.ng/rcb/lm/v1/loan-type', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer fe7f0f4f1a0843ea56a8f7a90d8a482ef6c26dee'
                ],
                'body' => json_encode([
                    'loan_type' => [
                        'name' => $this->data['package_name'],
                        'min_amount' => $this->data['min_amount'],
                        'max_amount' => $this->data['max_amount'],
                        'min_tenure' => 0,
                        'max_tenure' => 0,
                        'transaction_fee_frequency' => 'RECURRING',
                        'interest_rate' => $this->data['interest_rate'],
                        'interest_frequency' => 'ONCE',
                        'model' => 'STRAIGHT-LINE',
                        'currency' => $this->data['currency'],
                        'repayment_frequency' => 'MONTHLY',
                        'owner_id' => $this->data['owner_id'],
                        'owner_name' => $this->data['owner_name'],
                        'owner_type' => $this->data['owner_type'],
                        'administrative_fee_rate' => $this->data['administrative_fee_rate'],
                        'administrative_fee_frequency' => 'ONCE',
                        'payment_method_ids' => [9, 10],
                        'description' => $this->data['description'],
                        'min_approval' => $this->data['min_approval'],
                        'start_date' => $this->data['start_date'],
                        'end_date' => $this->data['end_date'],
                        'requirements' =>  [(object)['id' => 1, 'compulsory'=> true]]
                    ]
                ])
            ]);

        $promise->then(
            function (ResponseInterface $res) {
                dd($res->getStatusCode());
            }
        );

    }
}
