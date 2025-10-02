<?php
namespace App\Http\Controllers\admin\CRM\Api\ActionTab\Mailing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Schema;
use App\Exports\MailingExport;
use Maatwebsite\Excel\Facades\Excel;
class IndexController extends Controller
{
    public function index(){
        $ids = getUsersIds();
        $messgaes = Message::whereIn('user_id',$ids)->paginate(15);
        $result = [
            'data'         => $this->responseData($messgaes->items()),
            'current_page' => $messgaes->currentPage(),
            'from'         => $messgaes->firstItem(),
            'last_page'    => $messgaes->lastPage(),
            'links'        => [],
            'per_page'     => $messgaes->perPage(),
            'to'           => $messgaes->lastItem(),
            'total'        => $messgaes->total(),
        ];
        $messgaes->load('user');
        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }
    
    public function show($id){
        $document = Message::find($id);
        $document->load('user');
        $result = [
            'id'=>$document->id,
                'user_id'=>$document->user->id,
                'name'=>$document->user->name != null?$document->user->name:"name",
                'email'=>$document->user->email,
                'number'=>$document->user->phone,
                "created_at"=> date("Y M d",strtotime($document->created_at)),
                "subject"=> $document->subject,
                "message"=> $document->message.'dfhdsfdsyfyuyriuweyruwyeugqwheqweqwjgeywqfewqhsdgdssfdvhvegwfewewewyeqwyeqw',
                'status'=>$document->status,
        ];
        
        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }

    public function store(Request $request){
        $request->validate([
            'ids'=>'required|array',
            'ids.*'=>'required|numeric|exists:users,id',
            'message'=>'required|string',
            'subject'=>'required|string',
        ]);
        foreach($request->ids as $id){
            Message::create([
                'user_id'=>$id,
                'message'=>$request->message,
                'subject'=>$request->subject,
            ]);
        }
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
    {
        $idsUser = getUsersIds();
    
        $query = Message::whereIn('user_id', $idsUser);
    
        if (isset($request->search) && is_array($request->search)) {
            foreach ($request->search as $filter) {
                if (isset($filter['key'], $filter['value'])) {
                    $key = $filter['key'];
                    $value = $filter['value'];
    
                    if (Schema::hasColumn('messages', $key)) {
                        $query->where($key, 'LIKE', "%{$value}%");
                    }
                    elseif (strpos($key, '.') !== false) {
                        list($relation, $column) = explode('.', $key);
                        $query->whereHas($relation, function ($q) use ($column, $value) {
                            $q->where($column, 'LIKE', "%{$value}%");
                        });
                    }
                }
            }
        }
        $results = $query->paginate(15);
         $result = [
            'data'         => $this->responseData($results->items()),
            'current_page' => $results->currentPage(),
            'from'         => $results->firstItem(),
            'last_page'    => $results->lastPage(),
            'links'        => [],
            'per_page'     => $results->perPage(),
            'to'           => $results->lastItem(),
            'total'        => $results->total(),
        ];
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
        // return response()->json($result);
    }
    
    
               

    public function Filterstatus(Request $request){
        $ids = getUsersIds();
        $users = [];
        if($request->type){
            $users = Message::whereIn('user_id',$ids)->with(['user'])->where('status','<>','1')->paginate(15);
        }else{
            $users = Message::whereIn('user_id',$ids)->with(['user'])->paginate(15);
        }

        $result = [
            'data'         => $this->responseData($users->items()),
            'current_page' => $users->currentPage(),
            'from'         => $users->firstItem(),
            'last_page'    => $users->lastPage(),
            'links'        => [],
            'per_page'     => $users->perPage(),
            'to'           => $users->lastItem(),
            'total'        => $users->total(),
        ];
        
        $this->setData($result);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    
     public function responseData($documents){
        $data = [];
        foreach($documents as $document){
            if($document->user != null){
            $data [] = [
                'id'=>$document->id,
                'user_id'=>$document->user->id,
                'name'=>$document->user->name != null?$document->user->name:"name",
                'email'=>$document->user->email,
                'number'=>$document->user->phone,
                "created_at"=> date("Y M d",strtotime($document->created_at)),
                "subject"=> $document->subject,
                "message"=> $document->message,
                'status'=>$document->status,
                
            ];
            }
        }
        return $data;
    }

    public function export()
    {
        try {
            // Store the export as an Excel file
            Excel::store(new MailingExport(), 'upload/excel/export/mailing.xls', 'public');
    
            // Set the response message and data
            $this->setMessage("success");
            $this->setData('upload/excel/export/mailing.xls');
    
            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
