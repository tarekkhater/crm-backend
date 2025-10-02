<?php

namespace App\Http\Controllers\admin\finance\transactions;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // type [deposit,Bouns,Balance,withdrawl]
    public function index(Request $request){
        $ids = getUsersIds();
        $transactions = Transaction::where('user_id',$ids)->paginate(20);
        $this->setMessage("success");
        $this->setData($transactions);
        return $this->sendApiResonse();
    }

    public function user($id){
        $deposites = Transaction::where('user_id',$id)->paginate(20);
        $this->setMessage("success");
        $this->setData($deposites);
        return $this->sendApiResonse();
    }



    public function store(Request $request){
        $request->validate([

        ]);

        Transaction::create([

        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function update(Request $request){
        $request->validate([

        ]);

        Transaction::create([

        ]);

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

}
