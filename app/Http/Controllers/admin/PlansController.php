<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Plan;

class PlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.plans-list', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        if (Plan::count() == 18) {
            return redirect()->back()->with('failure', 'You can only add 18 plans');
        }

        $input = $this->getData($request);
        $input['features'] = array_filter($request->features); // remove any null values in array
  
        Plan::create($input);
        return redirect()->route('admin.plans.index')->with('success', 'Plan was successfully added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $plan = Plan::findOrFail($id);
        return view('admin.plans.plans-edit', compact('plan'));
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
        $plan = Plan::findOrFail($id);
        $request->validate([
            'name' => 'string|min:1|max:255|required',
            'name' => ['required', 'min:1', 'max:255', 'string', Rule::unique('plans')->ignore($plan->name, 'name')],
            'color' => 'string|min:1|max:255|required',
            'icon' => 'nullable',
            'amount' => 'integer|min:1|required',
            'status' => 'boolean|nullable',
            'features' => 'required|array'
        ]);
        

        $input = $request->all();
        $input['features'] = array_filter($request->features); // remove any null values in array
        
        // if ($image = $request->file('icon')) {
        //     $destinationPath = 'icons/';
        //     $profileImage = "icon-" . time() . "." . $image->getClientOriginalExtension();
        //     $image->move($destinationPath, $profileImage);
        //     $input['icon'] = "$profileImage";
        // }else{
        //     unset($input['icon']);
        // }
        
        $plan->update($input);

        return redirect()->route('admin.plans.index')->with('success', 'Plan Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required|unique:plans',
            'color' => 'string|min:1|max:255|required',
            'icon' => 'nullable',
            'amount' => 'integer|min:1|required',
            'status' => 'boolean|nullable',
            'features' => 'required|array'
        ];
        return $request->validate($rules);
    }
}
