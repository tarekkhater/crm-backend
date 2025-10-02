<?php

namespace App\Http\Controllers\admin\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\Integration;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndexController extends Controller
{

    public function __construct() {
    }

    public function index(){
        $data = Integration::with(['user'])->get();
        $this->setData($data);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function store(Request $request){

        // $request->validate([

        // ]);

        $data = $request->all();
        $user = Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'branch_id' => $data['source'],
            'type_id' => $data['type_id'],
            'password' => Hash::make($data['password']),
            'pass' => $data['password'],
            'image' => 'faild',
        ]);
        $user->roles()->create([
            'role_id'=>$data['role'],
            'user_type'=>'App\Models\Admin',
        ]);
        $user->Integration()->create([
            'number_leads'=>$data['number'],
            'token'=>JWTAuth::fromUser($user),
        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function ChangeStatus(Request $request){
        $integration = Integration::find($request->id);
        $integration->expired = 1;
        $integration->save();
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function affiliators(Request $request)
    {
        $perPage = (int)($request->get('per_page', 25));
        $q = $request->get('q');

        $query = Admin::withoutGlobalScopes()
            ->with(['countries', 'type'])
            ->where(function ($w) {
                $w->whereNotNull('token_affilator')
                  ->orWhere('sub_type_id', 3)
                  ->orWhereHas('Integration');
            });

        if ($q) {
            $query->where(function ($s) use ($q) {
                $s->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $paginator = $query->orderByDesc('id')->paginate($perPage);

        $data = $paginator->getCollection()->map(function (Admin $u) {
            $sourceName = null;
            if (isset($u->branch_id) && $u->branch_id) {
                $src = DB::table('sources')->where('id', $u->branch_id)->first();
                $sourceName = $src->name ?? null;
            }

            return [
                'id'      => $u->id,
                'name'    => $u->name,
                'email'   => $u->email,
                'status'  => $u->status,
                'sources' => [
                    'id'   => $u->branch_id ?? null,
                    'name' => $sourceName,
                ],
                'pass'     => $u->pass ?? null,
                'password' => $u->getOriginal('password'),
                'token_affilator' => $u->token_affilator ?? null,
            ];
        })->values();

        return response()->json([
            'message'    => 'success',
            'count'      => $paginator->total(),
            'data'       => $data,
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page'     => $paginator->perPage(),
                'last_page'    => $paginator->lastPage(),
            ],
        ]);
    }




}
