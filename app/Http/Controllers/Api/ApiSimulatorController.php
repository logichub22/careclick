<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiSimulatorController extends Controller
{
    public function apiHome(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }

    public function getStates(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0/states', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }

    public function getIncomeClasses(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0/income-classes', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }

    public function getCountries(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0/countries', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }

    public function marital(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0/marital-statuses', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }

    public function getCustomer(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $res = $client->request('GET', 'http://104.131.174.54:7171/api/v1.0/customers?phone=254723067310', [
            'headers' => [
                'api-key' => 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2'
            ],
            'auth' => [null, 'dzPr62CWYcmafbu8PEefJXjgePu33d7XKh6Udez2']
        ]);

        echo $res->getBody();
    }
}
