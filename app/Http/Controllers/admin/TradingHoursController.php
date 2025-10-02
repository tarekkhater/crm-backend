<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CurrencyPair;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class TradingHoursController extends Controller
{
    public function __construct()
    {

        $this->middleware('RoleMiddleware:view-trading-hours,edit-trading-hours')
        ;

        $this->middleware('RoleMiddleware:edit-trading-hours')->only('edit','update')
        ;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $currency_pairs = CurrencyPair::all();
return view('admin.trading_hours.index',compact('currency_pairs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $currency_pair = CurrencyPair::find($id);

        $openArray = json_decode($currency_pair->open_at, true);

        if (isset($openArray)) {
                foreach ($openArray as $array) {
                    $parts = explode('-', $array);
                    $startTime[] = $parts[0];

                    $endTime[] = $parts[1];

                    //$startTime[] = \Carbon\carbon::createFromTimeString($start);

                   // $endTime[] = \Carbon\carbon::createFromTimeString($end);

                }

        } else {

            $startTime = $currency_pair->open_at;

            $endTime = $currency_pair->close_at;

                //$startTime = \Carbon\carbon::createFromTimeString($currency_pair->open_at);

                //$endTime = \Carbon\carbon::createFromTimeString($currency_pair->close_at);

        }

        //dd($startTime);
        $daysArray = json_decode($currency_pair->days, true);



return view('admin.trading_hours.edit',compact('currency_pair','daysArray','startTime','endTime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $this->validate($request, [
            'days' => 'required',
            'open_at' => 'required',
            'close_at' => 'required',
            ]);
        $currency_pair = CurrencyPair::find($id);
        $days=json_encode($request['days']);

        $currency_pair->update([
            "days"=>$days,
            "open_at"=>$request['open_at'],
            "close_at"=>$request['close_at']
        ]);
        return redirect()->back()->with('success', 'Currency successfully Update.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \DB::table("roles")->where('id',$id)->delete();
return redirect()->route('admin.roles.index')
->with('success','Role deleted successfully');
    }
}
