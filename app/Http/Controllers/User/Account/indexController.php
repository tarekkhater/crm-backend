<?php

namespace App\Http\Controllers\User\Account;

use Exception;
use App\Models\User;
use App\Models\Account;
use App\Models\WireAccount;
use Illuminate\Http\Request;
use App\Rules\NoHtmlInjection;
use App\Rules\MatchOldPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\User\Account\UpdateRequest;
use App\Http\Requests\User\Account\UploadImageRequest;

class indexController extends Controller
{

    public function index(Request $request)
    {
        $user = AuthApi();
        $this->setData($user->userInfo);
        return $this->sendApiResonse();
    }


    public function updateimage(UploadImageRequest $request)
    {
        $file = $request->file('file');
        $mimeType = $file->getMimeType();
        $realMime = getRealMimeType($file);
        $realtype = explode(".", $file->getClientOriginalName());
        if ($realMime !== 'image/jpeg' && $realMime !== 'image/png' && $realMime !== 'image/gif' && !in_array($realtype[count($realtype) - 1], ['jpg', 'png', 'jpeg'])) {
            $this->setStatus(422);
            $this->setMessage("Invalid image file");
            return $this->sendApiResonse();
        }

        try {
            $user = AuthApi();
            $relativePath = str_replace('https://demo.fx-hub.net/', '', $user->avatar);


            if (Storage::disk('public')->exists($relativePath)) {
                Storage::disk('public')->delete($relativePath);
            }
            $data = $request->all();
            $user->avatar = uploadRealImage($data['file'], 'users/');
            $user->save();
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (Exception $e) {
            return $e;
        }
    }







    public function update(UpdateRequest $request)
    {
        $user = AuthApi();
        if ($request->email != $user->email) {
            $request->validate([
                'email' => ['required', 'email', 'max:255', 'unique:users,email', new NoHtmlInjection],
                'password' => ['required', new MatchOldPassword],
            ]);
            $user->email_verified_at = null;
            $this->setStatus(422);
             $this->setMessage("success");
            return $this->sendApiResonse();
        }
        try {

            $data = $request->all();

            $user->update([
                'name' => $data['name'],
                'surname' => $data['surname'],
                'email' => $data['email'],
                'country' => $data['country'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'permanent_address' => $data['permanent_address'],
                'postal' => $data['postal'],
            ]);
            $user->userInfo()->update([
                'cur' => $data['currency'],
            ]);
            $this->setMessage("success");
            return $this->sendApiResonse();
        } catch (Exception $e) {
            return $e;
        }
    }


    public function store(Request $request)
    {
        $data = $this->getData($request);
        $account = Account::create($data);
        $data['status'] = 1;
        $data['data'] = $account;
        $this->setData($data);
        return $this->sendApiResonse();
    }

    public function wireAccountStore(Request $request)
    {
        $data = $this->getWData($request);
        $account = WireAccount::create($data);
        $data['status'] = 1;
        $data['data'] = $account;
        $this->setData($data);
        return $this->sendApiResonse();
    }



    protected function getWData(Request $request)
    {
        $rules = [
            'account_name' => 'string|min:1|max:255|required',
            'account_number' => 'string|min:1|max:1000|required',
            'bank_country' => 'string|min:1|required',
            'bank_currency' => 'string|min:1|required',
            'bank_name' => 'string|min:1|required',
            'bank_branch' => 'string|min:1|required',
            'bank_address' => 'string|min:1|required',
            'sort_code' => 'string|min:1|required',
            'routine_number' => 'string|min:1|required',
            'swift_code' => 'string|min:1|required',
            'iban_number' => 'string|min:1|required',
            'account_label' => 'string|nullable',
        ];
        $data = $request->validate($rules);
        $data['user_id'] = auth()->id();
        return $data;
    }

    protected function getData(Request $request)
    {
        $rules = [
            'type' => 'string|min:1|required',
            'wallet' => 'string|nullable',
            'address' => 'string|nullable',
            'wire_id' => 'integer|nullable',
        ];

        $data = $request->validate($rules);

        $data['user_id'] = auth()->id();

        return $data;
    }
}
