<?php
namespace App\Http\Controllers\admin\ActionTab\KYC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Identity;
use Schema;
use App\Exports\KYCExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Document;

class IndexController extends Controller
{
    public function index(){
        $ids = getUsersIds();
        $idantity = Identity::whereIn('user_id',$ids)->with(['user','modified'])->paginate(15);
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
        $data = [];
        // Define a map of allowed search keys to model fields
        // $allowedFilters = [
        //     'Email' => 'email',
        //     'Name' => 'name',
        //     'phone' => 'number',
        //     // Add more key-to-field mappings here as needed
        // ];

        // Start the query with the base condition
        

        // Check if the 'search' parameter exists and is an array
        if (isset($request->search) && is_array($request->search)) {
            foreach ($request->search as $filter) {
                // Ensure each filter has both a 'key' and a 'value'
                // if (isset($filter['key'], $filter['value']) && array_key_exists($filter['key'], $allowedFilters)) {
                //     $field = $allowedFilters[$filter['key']];

                //     // Handle relationship fields if necessary (e.g., 'user.email')
                //     if (str_contains($field, '.')) {
                //         [$relation, $column] = explode('.', $field);
                //         $query->whereHas($relation, function ($q) use ($column, $filter) {
                //             $q->where($column, 'LIKE', "%{$filter['value']}%");
                //         });
                //     } else {
                //         // Handle direct fields on the Identity model
                //         $query->where($field, 'LIKE', "%{$filter['value']}%");
                //     }
                // }
                if($filter['value'] != ''){
                     $data[] = [$filter['key'],$filter['value']];
                }
            }
        }
        
        // Paginate the results
        $results = Identity::whereIn('user_id', $idsUser)->with(['user'])->paginate(15);
        if(count($data) > 0){
            $results = Identity::whereIn('user_id', $idsUser)->where($data)->with(['user'])->paginate(15);
        }
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

        $this->setMessage("success");
        $this->setData($result);
        return $this->sendApiResonse();
    }



    public function ChangeStatus(Request $request,$id){
        if($id > 0){
         if($request->type == 1){
            Document::where('id',$request->id)->update([
                'status'=>"$request->status",
                'note'=>isset($request->note)?$request->note:null,
            ]);
            $documens = Document::where('identity_id',$id)->where('status','1')->get();
            if(count($documens) > 3){
               $users = Identity::find($id)->update([
                    'status'=>"$request->status",
                    'modified_by'=>auth()->user()->id,
                ]); 
            }
            }else{
                $users = Identity::find($id)->update([
                    'status'=>"$request->status",
                    'modified_by'=>auth()->user()->id,
                    'note'=>isset($request->note)?$request->note:null,
                ]);
                if(isset($request->name) && is_array($request->name)){
                    // foreach($request->name as $index=>$value){
                        Document::where('identity_id',$id)->whereIn('title',$request->name)->update([
                            'status'=>"$request->status",
                            'note'=>isset($request->note)?$request->note:null,
                        ]);
                        Document::where('identity_id',$id)->whereNotIn('title',$request->name)->update([
                            'status'=>"1",
                        ]);
                    // }
                }else{
                    Document::where('identity_id',$id)->update([
                        'status'=>"$request->status",
                    ]);
                }
                
                
            }   
             $this->setMessage("success");
        }else{
             $this->setStatus(422);
             $this->setMessage("try again please");
        }
        
       
        return $this->sendApiResonse();
    }
    
     /**
     * Upload/Update KYC document for specific field
     */
   

public function storeImage(Request $request)
{
  
        
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120', // 5MB max
            'user_id' => 'required|integer|exists:users,id',
            'document_type' => 'required|string|in:front_id,back_id,selfy,por',
            'title' => 'sometimes|string|max:255'
        ]);

        if ($validator->fails()) {
            $this->setMessage($validator->errors()->first());
            return $this->sendApiResonse(null, 400);
        }


        // 2. Handle file upload
        $file = $request->file('document');
        $fileName = time() . '_' . $request->document_type . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('kyc_documents', $fileName, 'public');
        $fileUrl = Storage::url($filePath);

        // 3. Find the user
        $user = User::findOrFail($request->user_id);

        // 4. Get or create Identity record for the user
        $identity = Identity::firstOrCreate(
            ['user_id' => $user->id],
            ['status' => 0]
        );

        // 5. Update only the uploaded document field
        $identity->{$request->document_type} = $fileUrl;
        $identity->status = 0; // Reset status to pending
        $identity->save();

        // 6. Optionally save a document entry
        $identity->documents()->create([
            'value' => $fileUrl,
            'title' => $request->input('title', ucfirst(str_replace('_', ' ', $request->document_type))),
        ]);

        $this->setMessage("KYC document uploaded successfully.");
        return $this->sendApiResonse($identity->fresh(), 201);

}

/**
     * Replace specific KYC document field
     */
    public function replace(Request $request, $id)
    {
        try {
            $kyc = Document::find($id);
            
            if (!$kyc) {
                $this->setMessage("KYC record not found");
                return $this->sendApiResonse(null, 404);
            }

            // Validate the request
            $validator = Validator::make($request->all(), [
                'document' => 'required|file|mimes:jpeg,png,jpg,pdf|max:5120',
                'document_type' => 'required|string|in:front_id,back_id,front_credit_card,back_credit_card,selfy,por',
                'title' => 'sometimes|string|max:255'
            ]);

            if ($validator->fails()) {
                $this->setMessage($validator->errors()->first());
                return $this->sendApiResonse(null, 400);
            }

            $documentType = $request->document_type;

            // Delete old file if exists
            $oldFileUrl = $kyc->$documentType;
            if ($oldFileUrl) {
                $oldFilePath = str_replace('/storage/', '', $oldFileUrl);
                if (Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            }

            // Handle new file upload
            $file = $request->file('document');
            $fileName = time() . '_' . $documentType . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('kyc_documents', $fileName, 'storage');
            $fileUrl = Storage::url($filePath);

            // Update KYC record
            $updateData = [
                "value" => $fileUrl,
                'updated_at' => now(),
            ];

            // Update title if provided
            if ($request->has('title')) {
                $updateData['title'] = $request->title;
            }

            $kyc->update($updateData);

            $this->setMessage("KYC document replaced successfully");
            return $this->sendApiResonse($kyc->fresh(), 200);

        } catch (\Exception $e) {
            $this->setMessage("Failed to replace KYC document: " . $e->getMessage());
            return $this->sendApiResonse(null, 500);
        }
    }

      public function deleteDocument(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'kyc_id' => 'required|integer|exists:documents,id',
                'document_type' => 'required|string|in:front_id,back_id,front_credit_card,back_credit_card,selfy,por'
            ]);

            if ($validator->fails()) {
                $this->setMessage($validator->errors()->first());
                return $this->sendApiResonse(null, 400);
            }

            $kyc = Document::find($request->kyc_id);
            
            if (!$kyc) {
                $this->setMessage("KYC record not found");
                return $this->sendApiResonse(null, 404);
            }

            $documentType = $request->document_type;
            $fileUrl = $kyc->$documentType;

            // Delete file from storage
            if ($fileUrl) {
                $filePath = str_replace('/storage/', '', $fileUrl);
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
            }

            // Clear the document field
            $kyc->delete();

            $this->setMessage("KYC document deleted successfully");
            return $this->sendApiResonse(['deleted_field' => $documentType], 200);

        } catch (\Exception $e) {
            $this->setMessage("Failed to delete KYC document: " . $e->getMessage());
            return $this->sendApiResonse(null, 500);
        }
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
