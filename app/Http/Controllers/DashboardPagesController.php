<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\UserPlan;
use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;

class DashboardPagesController extends Controller
{

    public function investments(){
        $investments = [];
        $completed_nvestments = auth()->user()->plans()->whereStatus(2)->get();
        $active_investments = auth()->user()->activePlans()->whereStatus(1)->get();
        $pending_investments = auth()->user()->plans()->whereStatus(0)->get();
        return view('backend.investments.index', compact('investments','active_investments','pending_investments','completed_nvestments'));
    }

    public function postImage(Request $request){
        $user = auth()->user();
        $this->validate($request, ['file' => 'image' ]);
        $file = $request->file('file');
        $folder = 'uploads/';
        $uniqid = uniqid();
        $mainFileName = $uniqid . '.' . $file->getClientOriginalExtension();
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0755, true);
        }

        $image = $request->file('file');
        $img = Image::make($image->path());
        $img->resize(400, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(public_path($folder) . $mainFileName);

        $imageUrl = '/'.$folder . $mainFileName;

//        $userItem = User::find($user->id);
//        $userItem->addMedia(public_path($imageUrl))->toMediaCollection('media');


        return response()->json([
            'image'      => $imageUrl
        ]);
    }

    public function transactions(){
    $transactions = Transaction::whereUserId(auth()->id())->latest()->get();
//        if(count($deposits) > 0){
////            $deposit = Deposit::whereUserId(auth()->id())->latest()->first();
//        }else{
//            $deposit = null;
//        }
        return view('backend.transactions', compact('transactions'));
    }

}
