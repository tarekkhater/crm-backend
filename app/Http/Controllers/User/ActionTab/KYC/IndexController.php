<?php

namespace App\Http\Controllers\User\ActionTab\KYC;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\KYC\KYCRequest;
use App\Http\Requests\User\KYC\UpdateKYCRequest;
use App\Http\Resources\User\KYC\KYCResource;
use App\Models\Document;
use App\Models\Identity;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->user = AuthApi();
    }

    public function index()
    {
        $idantity = Identity::with(['user','modified','documents'])
            ->whereUserId($this->user->id)
            ->paginate(15);

        $data = [
            'data'         => KYCResource::collection($idantity->items()),
            'current_page' => $idantity->currentPage(),
            'from'         => $idantity->firstItem(),
            'last_page'    => $idantity->lastPage(),
            'links'        => $idantity->linkCollection(),
            'per_page'     => $idantity->perPage(),
            'to'           => $idantity->lastItem(),
            'total'        => $idantity->total(),
        ];

        $this->setMessage("success");
        $this->setData($data);
        return $this->sendApiResonse();
    }

    public function store(Request $request)
    {
        try {
            // ارفع كل ملف مرة واحدة فقط
            $frontId  = $request->file('front_id')?uploadRealImage($request->file('front_id'), 'identity/'):"hh";
            $backId   = $request->file('back_id')?uploadRealImage($request->file('back_id'), 'identity/'):"hh";
            $frontCC  = $request->file('front_credit_card')?uploadRealImage($request->file('front_credit_card'), 'identity/'):"hh";
            $backCC   = $request->file('back_credit_card')?uploadRealImage($request->file('back_credit_card'), 'identity/'):"hh";
            $selfy    = $request->file('selfy')?uploadRealImage($request->file('selfy'), 'identity/'):"hh"; // عمود DB اسمه selfy
            $por      = $request->file('por')?uploadRealImage($request->file('por'), 'identity/'):"hh";

            $identity = $this->user->identity()->create([
                'front_id'          => $frontId,
                'back_id'           => $backId,
                'front_credit_card' => $frontCC,
                'back_credit_card'  => $backCC,
                'selfy'             => $selfy,     // انتبه: selfy
                'por'               => $por,
                'status'            => 0,
            ]);

            $identity->documents()->createMany([
                ['value' => $frontId, 'title' => 'Front Id'],
                ['value' => $backId,  'title' => 'Back Id'],
                ['value' => $frontCC, 'title' => 'Front Credit Card'],
                ['value' => $backCC,  'title' => 'Back Credit Card'],
                ['value' => $selfy,   'title' => 'Selfie'],
                ['value' => $por,     'title' => 'POR'],
            ]);

            $this->setMessage("success");
            $this->setStatus(200);
        } catch (Exception $e) {
            $this->setStatus(422);
            $this->setMessage("error: " . $e->getMessage());
        }

        return $this->sendApiResonse();
    }

    public function update(UpdateKYCRequest $request, $id)
    {
        try {
            $identity = Identity::findOrFail($id);
            
            $fieldName = $this->mapIdentityKey($request->input('name'));
            
            $newPath = uploadRealImage($request->file('file'), 'identity/');
            
            $oldPath = $identity->{$fieldName};
            if (!empty($oldPath) && $oldPath !== 'hh') {
                $cleanPath = str_replace('storage/', '', $oldPath);
                if (Storage::disk('public')->exists($cleanPath)) {
                    Storage::disk('public')->delete($cleanPath);
                }
            }
            
            $identity->update([$fieldName => $newPath]);
            
            Document::where('identity_id', $id)
                ->where('title', $this->getDocumentTitle($fieldName))
                ->update(['value' => $newPath]);

            $this->setData([
                'url' => Storage::disk('public')->url($newPath),
                'field' => $fieldName,
            ]);

            $this->setMessage("success");
            return $this->sendApiResonse();

        } catch (Exception $e) {
            $this->setStatus(422);
            $this->setMessage("error: " . $e->getMessage());
            return $this->sendApiResonse();
        }
    }

private function getDocumentTitle(string $fieldName): ?string
    {
        $mapping = [
            'front_id' => 'Front Id',
            'back_id' => 'Back Id',
            'front_credit_card' => 'Front Credit Card',
            'back_credit_card' => 'Back Credit Card',
            'selfy' => 'Selfie',
            'por' => 'POR',
        ];
        
        return $mapping[$fieldName] ?? null;
    }

private function getFieldNameFromTitle(?string $title): ?string
    {
        if (empty($title)) {
            return null;
        }

        $mapping = [
            'Front Id' => 'front_id',
            'Back Id' => 'back_id',
            'Front Credit Card' => 'front_credit_card',
            'Back Credit Card' => 'back_credit_card',
            'Selfie' => 'selfy',  // انتبه: العمود في DB اسمه selfy مش selfie
            'POR' => 'por',
        ];

        return $mapping[$title] ?? null;
    }

    private function mapIdentityKey(string $name): string
    {
        // توحيد اسم العمود: قاعدة البيانات عندك selfy
        return $name === 'selfie' ? 'selfy' : $name;
    }

    public function FilterText(Request $request)
    {
        $data = [];
        $users = User::query();
        $columns = Schema::getColumnListing('users');
        foreach ($columns as $column) {
            $users->orWhere($column, 'LIKE', '%'.$request->input.'%');
        }
        $users = $users->where($data)->pluck('id');
        $users = Identity::with(['user','modified'])
            ->whereIn('user_id', $users)
            ->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function Filter(Request $request)
    {
        $from = date('Y-m-d',strtotime('2020-01-01'));
        $to   = date('Y-m-d');

        foreach ($request->search as $index => $value) {
            if ($value['key'] == 'date_from' && $value['value'] != null) {
                $from = date('Y-m-d',strtotime($value['value']));
            }
            if ($value['key'] == 'date_to' && $value['value'] != null) {
                $to = date('Y-m-d',strtotime($value['value']));
            }
        }

        $users = Identity::with(['user','modified'])
            ->whereBetween('created_at', [$from, $to])
            ->paginate(15);

        $this->setData($users);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
}
