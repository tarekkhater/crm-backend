<?php
namespace App\Http\Controllers\User\ActionTab\Mailing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
class IndexController extends Controller
{
       public function index(){
        // $messgaes = Message::where('user_id',AuthApi()->id)->get();
                $messgaes = Message::get();

        $this->setMessage("success");
        $this->setData($this->responseData($messgaes));
        return $this->sendApiResonse();
    }

    public function show($id){
        $document = Message::find($id);

        $result = [
            'id'=>$document->id,
                "created_at"=> date("Y M d",strtotime($document->created_at)),
                "subject"=> $document->subject,
                "message"=> $document->message.'dfhdsfdsyfyuyriuweyruwyeugqwheqweqwjgeywqfewqhsdgdssfdvhvegwfewewewyeqwyeqw',
                'status'=>$document->status,
        ];

        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }

     public function responseData($documents){
        $data = [];
        foreach($documents as $document){
            if($document->user != null){
            $data [] = [
                'id'=>$document->id,
                "created_at"=> date("Y M d",strtotime($document->created_at)),
                "subject"=> $document->subject,
                "message"=> $document->message,
                'status'=>$document->status,

            ];
            }
        }
        return $data;
    }

}
