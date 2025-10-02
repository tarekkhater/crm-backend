<?php
namespace App\Http\Controllers\admin\ActionTab\Trading;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TradingAccount;
use App\Models\User;
use App\Exports\TradingAccountExport;
use Maatwebsite\Excel\Facades\Excel;
use Schema;
class IndexController extends Controller
{
    public function index(){
        $ids = getUsersIds();
        $tradingaccount = TradingAccount::whereIn('user_id',$ids)->with(['user','offer','branch'])->paginate(15);
        $this->setMessage("success");
        $this->setData($tradingaccount);
        return $this->sendApiResonse();
    }


    public function Export(Request $request)
{
    try {
        // Store the export as an Excel file
        Excel::store(new TradingAccountExport(), 'upload/excel/export/TradingAccounts.xls', 'public');

        // Set the response message and data
        $this->setMessage("success");
        $this->setData('upload/excel/export/TradingAccounts.xls');

        return $this->sendApiResonse();
    } catch (\Exception $e) {
        // Handle any exceptions and provide a meaningful error response
        return response()->json([
            'message' => 'Export failed',
            'error' => $e->getMessage()
        ], 500);
    }
}




    public function store(Request $request){
            $this->validate($request, [
                'offer' => ['required', 'numeric','exists:packages,id'],
                'user_id' => ['required', 'numeric','exists:users,id'],             // must be at least 10 characters in length
            ]);
           try{
            $data = $request->all();
            TradingAccount::create([
                'offer_id' => $data['offer'],
                'user_id' => $data['user_id'],
            ]);
            $this->setMessage("success");
            return $this->sendApiResonse();
           }catch(Exceptions $e){

           }
    }


    public function update(Request $request,$id){
        $this->validate($request, [
            'offer' => ['required', 'numeric','exists:packages,id'],
            'user_id' => ['required', 'numeric','exists:users,id'],             // must be at least 10 characters in length
        ]);
       try{
        $data = $request->all();
        $TradingAcount = TradingAccount::find($id);
        $TradingAcount->update([
            'offer_id' => $data['offer'],
            'user_id' => $data['user_id'],
            'status' => $data['status'],
        ]);
        $this->setMessage("success");
        return $this->sendApiResonse();
       }catch(Exceptions $e){

       }
    }

    public function FilterByText(Request $request){
        $data = [];
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $users = $users->where($data)->pluck('id');
        $users = TradingAccount::with(['user','offer','branch'])->whereIn('user_id',$users)->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request){
        $data = [];
        $idsUser = getUsersIds();
        if (isset($request->search) && is_array($request->search)) {
            foreach ($request->search as $req) {
                $data[] = (object) [
                    "key" => $req['key'],
                    "value" => $req['value']
                ];
            }
        }
         $users = TradingAccount::whereIn('user_id',$idsUser)->where('status',1)->where($data)->paginate(15);

        return response()->json($users);


    }




}
