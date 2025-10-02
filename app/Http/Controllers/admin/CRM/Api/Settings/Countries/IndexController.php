<?php

namespace App\Http\Controllers\admin\CRM\Api\Settings\Countries;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Settings\Countries\IndexServices;

class IndexController extends Controller
{
    public $countries;
    public function __construct() {
        $this->countries = new IndexServices();
    }


    public function index(Request $requet){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->countries->all($requet));
        return $this->sendApiResonse();
    }

    public function show($id){
        $this->setMessage("jssjfhsfdkj");
        $this->setData($this->countries->show($id));
        return $this->sendApiResonse();
    }


    public function store(Request $request) {
        // Validate the input
        $request->validate([
            'iso' => 'required|string|max:2',
            'name' => 'required|string',
            'nicename' => 'required|string',
            'iso3' => 'nullable|string|max:3',
            'numcode' => 'nullable|numeric',
            'phonecode' => 'nullable|numeric',
        ]);

        // Call the service to store the country
        $country = $this->countries->store($request);

        // Set success message and return the newly created country
        $this->setMessage("Country created successfully");
        $this->setData($country);

        return $this->sendApiResonse();
    }


    public function update(Request $requet,$id){
        $id = $id;
        $this->countries->update($requet,$id);
        return $this->sendApiResonse();
    }


    public function destroy(Request $requet){
        $this->countries->destroy($requet);
        return $this->sendApiResonse();
    }
}