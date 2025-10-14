<?php

namespace App\Http\Resources\CRM\APi\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Deposit;
use App\Models\Trade;
use App\Models\TypeUser;
use App\Models\Favourite;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {



        $value = parent::toArray($request);
        $prevId = User::whereNull('deleted_at')
            ->where('id', '>', $value['id'])
            ->where('type_id', $value['type_id'])
            ->orderBy('id', 'asc')
            ->first();

        $nextId = User::whereNull('deleted_at')
            ->where('id', '<', $value['id'])
            ->where('type_id', $value['type_id'])
            ->orderBy('id', 'desc')
            ->first();
        $user = $this->resource;
        $token = JWTAuth::fromUser($user);
        $autoLoginUrl = "https://trade.quantumprime.app/login?token=$token";

        $result = [
            'verified' => $value['email_verified_at'] != null ? true : false,
            'withdraw' => $value['can_withdraw'] == '1' ? true : false,
            'allow_trade' => $value['allow_trade'] == '1' ? true : false,
            'allow_trade_after_hours' => $value['allow_trade_after_hours'] == '1' ? true : false,
            'online' => $value['no_of_logins'] == '1' ? 1 : 0,
        ];
        $result['id'] = $value['id'];
        $result['login_url'] = $autoLoginUrl;
        $result['auth_show'] = auth()->check() && in_array(auth()->user()->type_id, [3, 6]);

        $result['name'] = $value['name'] . ' ' . $value['surname'];
        $result['image'] = $value['avatar'];
        $result['next_id'] = $nextId->id ?? null;
        $result['prev_id'] = $prevId->id ?? null;
        $result['admin_id'] = $value['manager']['manager']['id'] ?? 0;


        $result['main']['general'] = [
            'id' => $value['id'],
            'name' => $value['name'],
            'email' => $value['email'],
            'phone' => $value['phone'],
            'surname' => $value['surname'],
            'currency' => $value['currency'],
            'manager' => isset($value['manager']['manager']['name'], $value['manager']['manager']['surname'])
                ? $value['manager']['manager']['name'] . ' ' . $value['manager']['manager']['surname']
                : 'not assign',
            'country' => $value['country'],
            'key' => $value['pass'],
            'plan' => optional(optional($value['user_info'])['plan'])['name'] ?? "No Plan",


            'type' => $value['type_user'] ? $value['type_user']['name'] : "user",
            'source' => $value['user_info']['source'] ? $value['user_info']['source']['name'] : "No Source",
            'statusLead' => optional(optional($value['user_info'])->status)->name ?? "No Status",
            'status' => $value['user_info']['status_id'],
            'plan_id' => $value['user_info']['plan_id'],
            'kyc' => count($value['identity']) > 0 ? true : false,
            'birth' => $value['birth'],
            'address' => $value['address'],
            'permanent_address' => $value['permanent_address'],
            'postal' => $value['postal'],

        ];

        $result['main']['personal'] = [
            'email' => $value['email'],
            'phone' => $value['phone'],
            'country' => $value['country'] != '' ? ['name'] : $value['country'],
            'address' => $value['address'],
            'permanent_address' => $value['permanent_address'],
            'postal' => $value['postal'],
            'joined' => date('Y M d', strtotime($value['created_at']))
        ];

        $result['money'] = [
            'total' => $value['user_info']['money'] + $value['user_info']['balance'],
            'balance' => $value['user_info']['money'],
            'trading_balance' => $value['user_info']['balance'],
            'pnl' => $value['user_info']['pnl'],
            'bouns' => Deposit::where('user_id', $value['id'])->where('type', 'bonus')->sum('amount'),
        ];
        $result['trades'] = [
            'open' => Trade::where('user_id', $value['id'])->whereStatus(0)->Where('is_pending_order', null)->count(),
            'close' => Trade::where('user_id', $value['id'])->whereStatus(1)->count(),
            'pending' => Trade::where('user_id', $value['id'])->whereStatus(0)->Where('is_pending_order', '<>', null)->count(),
            'assets' => Favourite::where('user_id', $value['id'])->count(),
        ];

        // $value['user_info']['bonus']

        $result['wallet'] =  [
            'awaiting' => $value['user_info']['awaiting_deposit'],
            'trading' => ($value['user_info']['awaiting_deposit'] == $value['user_info']['balance'])
                ? 0
                : abs((float)$value['user_info']['balance'] - (float)$value['user_info']['awaiting_deposit']),
        ];
        return $result;
    }
}
