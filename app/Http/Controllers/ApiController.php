<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ApiController extends Controller
{

    /**
     * Get data from jsonUrl and transform it into the requeted structure
     * to show in a DataTable
     */
    public function getData(Request $request)
    {

        $jsonUrl = 'http://feeds.spotahome.com/ads-housinganywhere.json';
        $client = new \GuzzleHttp\Client();
        $dataTable = [];

        //Curl url to get data
        try {
            $response = $client->request('GET', $jsonUrl);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error($e->getMessage());
        }

        //Transform data into custom array
        $data = $this->transformData(json_decode($response->getBody()->getContents(), true));

        //Only for testing purpuse
        $data = array_slice($data, 0, 16);

        //Laravel paginator does not send fit structure for this React DataTable
        //TODO adapt DataTable structure to get data for Laravel Paginator
        //$data = $this->paginate($data, 5);

        $dataTable['data'] = $data;

        //Harcoded data only for testing DataTable
        //TODO get real pagination
        $dataTable['links'] = '
            "first": "http:\/\/reacttable.test\/api\/source-data-transformed?page=1",
            "last": "http:\/\/reacttable.test\/api\/source-data-transformed?page=4",
            "prev": null,
            "next": "http:\/\/reacttable.test\/api\/source-data-transformed?page=2"
        ';

        $dataTable['meta'] = '{
            "current_page": 1,
            "from": 1,
            "last_page": 4,
            "links": [{
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            }, {
                "url": "http:\/\/reacttable.test\/api\/source-data-transformed?page=1",
                "label": "1",
                "active": true
            }, {
                "url": "http:\/\/reacttable.test\/api\/source-data-transformed?page=2",
                "label": "2",
                "active": false
            }, {
                "url": null,
                "label": "...",
                "active": false
            }, {
                "url": "http:\/\/reacttable.test\/api\/source-data-transformed?page=3",
                "label": "49",
                "active": false
            }, {
                "url": "http:\/\/reacttable.test\/api\/source-data-transformed?page=4",
                "label": "50",
                "active": false
            }, {
                "url": "http:\/\/reacttable.test\/api\/source-data-transformed?page=2",
                "label": "Next &raquo;",
                "active": false
            }],
            "path": "http:\/\/reacttable.test\/api\/source-data-transformed",
            "per_page": 5,
            "to": 5,
            "total": 16
        }';


        return response()->json($dataTable);
    }

    /**
     * Trasnform all json received data into requested array
     */
    protected function transformData($array)
    {
        return array_map(function ($value) {
            $title = explode(".", $value['Description'], 2);
            $image = "";
            if (is_array($value['Images']) && count($value['Images']) > 0) {
                $image = $value['Images'][0];
            }

            return [
                "title"     => $title[0],
                "address"   => $value['Address'],
                "city"      => $value['City'],
                "link"      => $value['Link'],
                "image"     => $image,
            ];
        }, $array);

    }
}
