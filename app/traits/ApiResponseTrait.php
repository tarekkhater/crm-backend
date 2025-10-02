<?php
namespace App\traits;

use Illuminate\Http\JsonResponse;
trait ApiResponseTrait{
    public $message = '';
    public $statusText = 'success';
    public $status= 200;
    public $data = [];




    public function setMessage($message){
        $this->message = $message;
    }

    public function setstatusText($statusText){
        $this->statusText = $statusText;
    }

    public function setStatus($status){
        $this->status = $status;
    }

    public function setData($data){
            $this->data = $data;
    }   


    public function sendApiResonse():JsonResponse
    {
        $data = [
            "message"   =>$this->message,
            "status"    =>$this->status,
        ];
        
        // if(!empty($this->data)){
             $data["data"] = $this->data;
        // }
        return new JsonResponse($data,$this->status);
    }


    
}
