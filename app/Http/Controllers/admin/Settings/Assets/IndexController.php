<?php

namespace App\Http\Controllers\admin\Settings\Assets;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\StoreRequest;
use App\Http\Requests\Admin\Asset\UpdateRequest;
use Illuminate\Http\Request;
use App\Services\Settings\Assets\IndexServices;
use App\Models\CurrencyPair;
use App\Models\Setting;

class IndexController extends Controller
{
    public $assets;
    public function __construct()
    {
        $this->assets = new IndexServices();
    }

    public function types(Request $requet)
    {
        $this->setData($this->assets->types());
        $this->setMessage("jssjfhsfdkj");
        return $this->sendApiResonse();
    }

    public function showByTypes(Request $requet)
    {
        $this->setData($this->assets->getbytype($requet->type));
        $this->setMessage("jssjfhsfdkj");
        return $this->sendApiResonse();
    }

    public function index(Request $requet)
    {
        $this->setData($this->assets->all($requet));
        $this->setMessage("success");
        // $this->updatePriceAssets();
        return $this->sendApiResonse();
    }

    public function show($id)
    {
        $this->setMessage("success");
        $this->setData($this->assets->show($id));
        return $this->sendApiResonse();
    }


    public function store(StoreRequest $request)
    {
        $data = $this->getData($request);
        $this->assets->store($request);
        return $this->sendApiResonse();
    }

    public function update(UpdateRequest $requet, $id)
    {
        // $request->validate([
        //   'ids' => 'required|array',
        //   'ids.*' => 'required|numeric|exists:currency_pairs,id',
        //     'value' => 'required|numeric',
        // ]);
        $id = $id;
        $this->assets->updated($requet, $id);
        return $this->sendApiResonse();
    }
    
    public function updatehoursAssets(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:currency_pairs,id',
            'open_at' => 'required',
            'close_at' => 'required',
            'days' => 'required|array',
        ]);
        $currency_pairs = CurrencyPair::where('id', $request->id)->first();
       
            $currency_pairs->update([
                "close_at" => $request->close_at,
                "open_at" => $request->open_at,
                 "days" => json_encode($request->days)
            ]);
        
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function leverage_update(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:currency_pairs,id',
            'value' => 'required|numeric|gt:-1',
        ]);
        $currency_pairs = CurrencyPair::whereIn('id', $request->ids)->get();
        foreach ($currency_pairs as $item) {
            $item->update([
                "leverage" => $request->value
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function sell_update(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:currency_pairs,id',
            'value' => 'required|numeric|gt:-1',
        ]);
        $currency_pairs = CurrencyPair::whereIn('id', $request->ids)->get();
        foreach ($currency_pairs as $item) {
            $item->update([
                "sell_spread" => $request->value
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function buy_update(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|numeric|exists:currency_pairs,id',
            'value' => 'required|numeric|gt:-1',
        ]);
        $currency_pairs = CurrencyPair::whereIn('id', $request->ids)->get();
        foreach ($currency_pairs as $item) {
            $item->update([
                "buy_spread" => $request->value
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'order' => 'required|array|min:1',
            'order.*' => 'required|numeric|exists:currency_pairs,id',
        ]);

        $this->assets->updateOrder($request->type, $request->order);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }



    public function destroy($id)
    {
        try {
            // Find the currency pair by ID or fail
            $currencyPair = CurrencyPair::findOrFail($id);

            // Delete the currency pair
            $currencyPair->delete();

            // Return a success response
            return response()->json(['message' => 'Delete done'], 200); // HTTP status code 200 for success
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Currency pair not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }







    protected function getData(Request $request)
    {
        $data = $request->all();
        $data['rate'] = $this->getCurRate($data['sym'], $data['base'], $data['type']);
        return $data;
    }


    public function updatePriceAssets()
    {
        $assets = $this->assets->allindex();
        if (Setting::where('key', 'disable_api')->first()->value == '0') {
            $this->setStatus(422);
            $this->setMessage("you Can Not update Assets");
            return $this->sendApiResonse();
        }
        foreach ($assets as $asset) {
            $asset->rate = $this->getCurRate($asset->sym, $asset->base, $asset->type);
            $asset->save();
        }
        $this->setMessage("success,updated");
        return $this->sendApiResonse();
    }
}
