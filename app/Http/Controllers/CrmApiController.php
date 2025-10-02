<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CrmApiController extends Controller
{
    public  function  crm_url(){
        return setting('crm_url');
    }
    public  function  token(){
        return setting('crm_token');
    }

//    public $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiRXhjZWwgIiwibmFtZSI6ImV4Y2VsIiwiQVBJX1RJTUUiOjE2MzE2MDExNDN9.psqp7lEghONM8v4ARh_gctdmgBfCYPrAzAccK5QWgAk';

    public function test(){
        return $this->addLead();
//        $url = $this->crm_url().'api/customers';
//        $response = Http::withHeaders(['authtoken' => $this->token])->get($url);
//        return $response;
    }

    public function addLead($data){
        $url = $this->crm_url().'/api/leads';

        $response = Http::withHeaders(['authtoken' => $this->token()])->asForm()->post($url, [
            'source' => 'excel',
            'name' => 'hello',
            'status' => '2',
            'phonenumber' => '00000',
            'email' => 'test@gmail.com',
            'city' => 'canda',
            'country' => 'canda',
            'address' => 'canda',
        ]);
        return $response;
    }
}
