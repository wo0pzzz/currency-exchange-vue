<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\ExCurrData;
use Illuminate\Support\Facades\Http;


class MainController extends Controller
{
    public function index(): View
    {
        return view('main', []);
    }

    public function getData(Request $request)
    {
        $request->validate([
            'curr_from' => 'required',
            'curr_to' => 'required',
            'amount' => 'required|numeric'
        ]);
        $req = [
            'from' => $request->input('curr_from'),
            'to' => $request->input('curr_to'),
            'amount' => $request->input('amount')
        ];

        $response = Http::get('https://anyapi.io/api/v1/exchange/convert?base='. $req['from'] .'&to='. $req['to'] .'&amount='. $req['amount'] .'&apiKey='.env('EXCHANGE_API_KEY'));
        $resp = $response->json();

        $findExisted = ExCurrData::where([
            'from_curr' => $req['from'],
            'to_curr' => $req['to'],
            'date' => $resp['lastUpdate']
        ])->first();

        if (!$findExisted) {
            ExCurrData::create([
                'from_curr' => $req['from'],
                'to_curr' => $req['to'],
                'from_amount' => $req['amount'],
                'to_amount' => $resp['converted'],
                'rate' => $resp['rate'],
                'date' => $resp['lastUpdate']
            ]);
        } else { // update existed
            $findExisted['to_amount'] = $resp['converted'];
            $findExisted['rate'] = $resp['rate'];
            $findExisted->save();
        }

        $allCurrData = ExCurrData::where([
           'from_curr' => $req['from'],
           'to_curr' => $req['to'],
        ])->orderBy('date', 'desc')->get();

        $data = [];
        $data['list'] = [];
        $data['max'] = $data['avg'] = $data['sum'] = 0;
        $data['min'] = $allCurrData[0]['to_amount'];
        $data['lastUpdate'] = date('d.m.Y', $resp['lastUpdate']);

        foreach ($allCurrData as $curr) {
            $data['list'][] = [
                'date' => date('d.m.Y', $curr['date']),
                'to_amount' => $curr['to_amount']
            ];
            if ($curr['to_amount'] >= $data['max']) $data['max'] = round($curr['to_amount'], 4);
            if ($curr['to_amount'] <= $data['min']) $data['min'] = round($curr['to_amount'], 4);
            $data['sum'] += $curr['to_amount'];
        }

        if (count($data['list']) > 0) {
            $data['avg'] = $data['sum'] / count($data['list']);
            $data['avg'] = round($data['avg'], 4);
        }

        return $data;
    }
}
