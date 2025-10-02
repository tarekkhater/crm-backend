<?php

namespace App\Http\Controllers\User\Plan;

use App\Models\InvProfit;
use App\Models\Package;
use App\Models\Plan;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Plan\PlanRequest;


class indexController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $this->setData($plans);
        $this->setMessage("success");
        return $this->sendApiResonse();

    }
    
    public function userPlan(){
        
        $plans = Package::where('price','>',0)->get();
        $this->setData($plans);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function investmentPlan(Request $request)
    {
        $user = Auth::user();
        $investment = UserPlan::whereUserId($user->id)->paginate(15);
        $this->setData($investment);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function updateInv(){
        $invProfits = InvProfit::whereStatus(1)->whereUserId(auth()->id())->get();
        $today = date('Y-m-d');
        foreach ($invProfits as $item){
            //CHECK IF PLAN IS ENDED
            $end_date = Carbon::createFromFormat('Y-m-d', $item->end_date);

            $convToday = Carbon::createFromFormat('Y-m-d', $today);

            $last_updated = getDifInDays($item->updated, $today);

            if($convToday->gt($end_date)){
                $item->profit = $item->total_profit;
                $item->status = 2;
                $item->updated = $today;
                //update userplan
              $this->updateUserPlan($item->user_plan_id, $item);
            }else {
                if($last_updated > 0){
                    $profitPerDay = $item->total_profit / $item->period;
                    $profitPerDay = number_format($profitPerDay,2);
                    $period = getDifInDays($item->start_date, $today);
                    $profit = $period * $profitPerDay;
                    //SAVE CHANGES
                    $item->profit = $profit;
                    $item->updated = $today;
                }
            }
            $item->save();
        }
    }

    public function updateUserPlan($plan_id, $data){
        $plan = UserPlan::whereId($plan_id)->first();
        $plan->profit = $data->profit;
        $plan->status = $data->status;
        $plan->start_date = $data->start_date;
        $plan->end_date = $data->end_date;
        $plan->earned = $plan->amount + ($plan->amount * ($plan->profit / 100));
        $plan->save();
    }

    public function store(PlanRequest $request){
        $data = $request->all();
        $user = Auth::user();
        $package = Package::findOrFail($data['plan_id']);
        $end_date = Carbon::now()->addMonths($package->period);
        if($package->period_type == 'week'){
            $end_date = Carbon::now()->addMonths($package->period);
        }

        if($package->period_type == 'day'){
            $end_date = Carbon::now()->addDays($package->period);
        }
        $amt = $data['amount'];
        $userPlan = new UserPlan();
        if($user->userInfo->money < $amt){
           $this->setData(['status'=>0]);
            $this->setMessage('Insufficient Balance, please fund account to continue');
        }else{
            $userPlan->units = $data['unit'];
            $userPlan->plan_id = $package->id;
            $userPlan->user_id = $user->id;
            $userPlan->start_date = date('Y-m-d');;
            $userPlan->end_date = $end_date;
            $userPlan->amount = $amt;
            $userPlan->status = 0;
            $userPlan->save();

            //SAVE INVPROFIT
            $userPlan->profit = 0;
            $userPlan->total_profit = $package->percent_profit;
            $userPlan->user_plan_id = $userPlan->id;
            $userPlan->period = getDifInDays($userPlan->start_date, $end_date->format('Y-m-d'));
            $invData = $userPlan->toArray();
            $invProfit = InvProfit::create($invData);
            $invProfit->trx = $this->trxId($invProfit->id);
            $invProfit->save();

            $this->setData(['status'=>1]);
            $this->setMessage('Investment purchase successful, awaiting approval!');
            // Session::flash('success', 'Investment purchase successful, awaiting approval!');
            $this->minusBalance($user, $amt, 'Investment purchase payment');
        }

        return $this->sendApiResonse();

    }
    private function trxId($id){
        return str_pad($id + 1, 10, "0", STR_PAD_LEFT);
    }

        public function setup(){

        $userPlan = UserPlan::whereUserId(auth()->id())->first();
        if(!$userPlan){
            return $this->activate(Package::whereStatus(1)->where('price','>',0)->first()->id);
        }
        $plan = Package::findOrFail($userPlan->plan_id);
       $plans = Package::whereStatus(1)->where('price','>',0)->get()->toArray();

       $this->setData(['plan'=>$plan,'plans'=>$plans]);
       $this->setMessage( "success");
       return $this->sendApiResonse();
    }
    public function activate($plan_id){
        $plans = UserPlan::whereUserId(auth()->user()->id)->whereStatus(0);
        if ($plans) {
            $plans->delete();
        }
        $package = Package::findOrFail($plan_id);
        $plan = new UserPlan();
        $plan->user_id = auth()->id();
        $plan->plan_id = $package->id;
        $plan->amount = 0;
        $plan->save();

        $this->setMessage("success");
        return $this->sendApiResonse();
    }

    public function upgrade($id) {
        try {
            $user = Auth::user();
            $plan = Plan::find($id);

            if ($user->userInfo->money < $plan->amount) {
                $this->setStatus(422);
                 $this->setMessage("You don't have enough balance to upgrade to this plan.");
                // return redirect()->back()->with('failure', "You don't have enough balance to upgrade to this plan.");
            }

            if ($user->plan == $plan->name) {
                 $this->setStatus(422);
                $this->setMessage("You're already in the {$plan->name} plan.");
                // return redirect()->back()->with('failure', "You're already in the {$plan->name} plan.");
            }

            DB::beginTransaction();
            $this->minusBalance($user, $plan->amount, 'Plan upgrade payment');
            // $user->update(['plan' => $plan->name]);
            DB::commit();

        $this->setStatus(200);
        $this->setMessage( "Plan Upgraded to " . $plan->name);
        return $this->sendApiResonse();
        } catch (\Throwable $th) {
            DB::rollback();
            $this->setMessage($th->getMessage());
            return $this->sendApiResonse();
            // return redirect()->back()->with('failure', $th->getMessage());
        }
    }



}
