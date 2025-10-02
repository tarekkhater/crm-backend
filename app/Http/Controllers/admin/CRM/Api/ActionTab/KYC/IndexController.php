<?php
namespace App\Http\Controllers\admin\CRM\Api\ActionTab\KYC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Identity;
use Schema;
use App\Exports\KYCExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
class IndexController extends Controller
{
    public function index(){
        $ids = getUsersIds();
        $idantity = Identity::whereIn('user_id',$ids)->with(['user','modified'])->paginate(5);
        $result = [
            'data'         => $this->responseData($idantity->items()),
        'current_page' => $idantity->currentPage(),
        'from'         => $idantity->firstItem(),
        'last_page'    => $idantity->lastPage(),
        'links'        => [],
        'per_page'     => $idantity->perPage(),
        'to'           => $idantity->lastItem(),
        'total'        => $idantity->total(),
        ];
        
        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }

    public function export()
    {
        try {
            // Store the export as an Excel file
            Excel::store(new KYCExport(), 'upload/excel/export/KYC.xls', 'public');
    
            // Set the response message and data
            $this->setMessage("success");
            $this->setData('upload/excel/export/KYC.xls');
    
            return $this->sendApiResonse();
        } catch (\Exception $e) {
            // Handle any exceptions
            return response()->json([
                'message' => 'Export failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function FilterText(Request $request){
        $data = [];
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach($columns as $column){
            $users->orWhere($column,'LIKE','%'.$request->input.'%');
        }
        $users = $users->where($data)->pluck('id');
        $users = Identity::with(['user','modified'])->whereIn('user_id',$users)->paginate(15);

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

    public function Filter(Request $request)
    {
        // Get the list of user IDs to filter
        $idsUser = getUsersIds();
    
        // Define a map of allowed search keys to model fields
        $allowedFilters = [
            'Email' => 'email',
            'Name' => 'name',
            'phone' => 'number',
            // Add more key-to-field mappings here as needed
        ];
    
        // Start the query with the base condition
        $query = Identity::whereIn('user_id', $idsUser)->with(['user']);
    
        // Check if the 'search' parameter exists and is an array
        if (isset($request->search) && is_array($request->search)) {
            foreach ($request->search as $filter) {
                // Ensure each filter has both a 'key' and a 'value'
                if (isset($filter['key'], $filter['value']) && array_key_exists($filter['key'], $allowedFilters)) {
                    $field = $allowedFilters[$filter['key']];
                    
                    // Handle relationship fields if necessary (e.g., 'user.email')
                    if (str_contains($field, '.')) {
                        [$relation, $column] = explode('.', $field);
                        $query->whereHas($relation, function ($q) use ($column, $filter) {
                            $q->where($column, 'LIKE', "%{$filter['value']}%");
                        });
                    } else {
                        // Handle direct fields on the Identity model
                        $query->where($field, 'LIKE', "%{$filter['value']}%");
                    }
                }
            }
        }
    
        // Paginate the results
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
        // Return JSON response
        return response()->json($result);
    }
    


    public function ChangeStatus(Request $request,$id){
        $users = Identity::find($id)->update([
            'status'=>$request->status,
            'modified_by'=>auth()->user()->id,
        ]);
        $this->setMessage("jssjfhsfdkj");
        return $this->sendApiResonse();
    }


    public function destroy(Request $request){
        $Identity = Identity::find($request->id);
        $Identity->delete();
        $this->setMessage("jssjfhsfdkj");
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
                'front_id'=>baseUrl().$document->front_id,
                'back_id'=>baseUrl().$document->back_id,
                'front_credit_card'=>baseUrl().$document->front_credit_card,
                'back_credit_card'=>baseUrl().$document->back_credit_card,
                'selfie'=>baseUrl().$document->selfie,
                'por'=>baseUrl().$document->por,
                'status'=>$document->status,
                'proof'=>$document->proof,
            ];
            }
        }
        return $data;
    }


}
