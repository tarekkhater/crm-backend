<?php

namespace App\Http\Controllers\admin\Users\Admin;
use App\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\IBUser;
use App\Models\UserManager;
use Illuminate\Support\Facades\Hash;
use Exception;
use App\Services\Users\Admin\IndexSearchServices;
use App\Services\Users\Admin\IndexFilterServices;
class IndexController extends Controller
{
    public function __construct()
    {
        $this->searchAgents = new IndexSearchServices();
        $this->filterAgents = new IndexFilterServices();
        $this->middleware('RoleMiddleware:view-settings_update-settings')->only('overnights');
        $this->middleware('RoleMiddleware:verify-all-emails')->only('verifyAccounts');
        $this->middleware('RoleMiddleware:view-transaction')->only('transactions');
        $this->middleware('RoleMiddleware:delete-transaction')->only('DelTrans');
        

    }

    public function index(Request $request){
        $users = Admin::where('type_id',3)->whereNotIn('id',[ auth()->id(),'1'])->paginate(15);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function store(Request $request)
{
    // التحقق من صحة البيانات
    // $request->validate([
    //     'name' => ['sometimes', 'string', 'max:255'],
    //     'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
    //     'branch_id' => ['sometimes', 'numeric', 'exists:branchs,id'],
    //     'password' => ['sometimes', 'string', 'min:6', 'sometimes_with:confirm_password', 'same:confirm_password'],
    //     'role' => ['sometimes', 'integer', 'exists:roles,id'],
    //     'type_id' => ['sometimes', 'integer'],
    //     'balance' => ['nullable', 'numeric'],
    //     'currency' => ['nullable', 'string'],
    //     'manager_id' => ['nullable', 'integer', 'exists:admins,id'],
    //     'leader_id' => ['nullable', 'integer', 'exists:admins,id'],
    //     'country' => ['sometimes', 'string', 'max:255'], // تحقق من صحة حقل البلد
    //     'phone' => ['sometimes', 'string', 'max:15'], // تحقق من صحة حقل رقم الهاتف
    // ]);

    try {
        $data = $request->all();

        // إنشاء المستخدم
        $user = Admin::create([
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'branch_id' => isset($request->source) ? $data['source'] : null,
            'type_id' => $data['type_id'],
            'sub_type_id' => $data['type_id'],
            'password' => Hash::make($data['password']),
            'image' => 'faild',
            'country' => $data['country'], // تخزين البلد
            'phone' => $data['phone'], // تخزين رقم الهاتف
            'email_verified_at'=>date("Y-m-d H-i-s"),
        ]);

      
        $role = Role::find($request->role);
        $user->assignRole($role);

        // التعامل مع حالات type_id المحددة
        if ($data['type_id'] == 6) {
            if (auth()->user()->type_id == 4) {
                UserManager::create([
                    'admin_id' => auth()->user()->id,
                    'user_id' => $user->id,
                    'type' => '0'
                ]);
            } else {
                UserManager::create([
                    'admin_id' => $data['manager_id'],
                    'user_id' => $user->id,
                    'type' => '0'
                ]);
            }
        }

        if ($data['type_id'] == 5) {
            IBUser::create([
                'balance' => $data['balance'],
                'currency' => $data['currency'],
                'user_id' => $user->id
            ]);
        }

        if (isset($request->leader_id) && ($data['type_id'] == 7 || $data['type_id'] == 8)) {
            UserManager::create([
                'admin_id' => $data['leader_id'],
                'user_id' => $user->id,
                'type' => '1'
            ]);
        }

        // إعداد بيانات الاستجابة
        $responseData = [
            'message' => 'success',
            'user' => $user,  // إرجاع المستخدم الذي تم إنشاؤه
        ];

        return response()->json($responseData, 201); // حالة استجابة 201 Created
    } catch (Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500); // إرجاع رسالة الخطأ
    }
}



    public function destroy(Request $request) {
        // تأكد من أن الحقل ids موجود ويتم التحقق من نوعه
        if (!is_array($request->ids) || empty($request->ids)) {
            $this->setMessage("No ids provided or invalid data format");
            return $this->sendApiResonse();
        }

        foreach($request->ids as $value) {
            // ابحث عن الإداري باستخدام معرفه
            $user = Admin::find($value);

            // تحقق مما إذا كان الإداري موجودًا
            if ($user) {
                // احذف الإداري إذا كان موجودًا
                $user->delete();
            } else {
                // إذا لم يكن موجودًا، أضف رسالة تفيد بعدم وجوده
                $this->setMessage("Admin with id $value not found");
            }
        }

        // بعد إتمام العملية، قم بإرجاع استجابة النجاح
        $this->setMessage("Success");
        return $this->sendApiResonse();
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'surname' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'branch_id' => ['sometimes', 'numeric', 'exists:branchs,id'],
            'password' => [
                'nullable',
                'string',
                'min:6',
                'required_with:confirm_password',
                'same:confirm_password'
            ],
            'confirm_password' => ['nullable', 'string', 'min:6'], // Add confirm_password validation
            'role' => ['sometimes', 'integer', 'exists:roles,id'],
            'type_id' => ['sometimes', 'integer'],
        ]);

        try {
            // Fetch user
            $user = Admin::findOrFail($id);

            // Update core fields
            $user->update([
                'name' => $request->input('name', $user->name),
                'surname' => $request->input('surname', $user->surname),
                'email' => $request->input('email', $user->email),
                'branch_id' => $request->input('branch_id', $user->branch_id),
                'type_id' => $request->input('type_id', $user->type_id),
                'country' => $request->input('country', $user->country),
                'phone' => $request->input('phone', $user->phone),
                'password' => $request->filled('password')
                    ? Hash::make($request->password)
                    : $user->password,
            ]);
            
            if($request->role){
                $role = Role::find($request->role);
                $user->syncRoles($role);
            }
            


            $this->setMessage("Update successful");
            return response()->json([], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }



        public function FilterByText(Request $request)
    {
    $users = $this->searchAgents->index($request);
        $this->setData($users);
        $this->setMessage("success");

        // Return the response
        return $this->sendApiResonse();
    }


    public function Filter(Request $request){

         $users = $this->filterAgents->index($request);
        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


}
