<?php

namespace App\Http\Controllers\admin\Certifications;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\UserCertificate;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PercentageCertificates;
class certificationController extends Controller
{
    public function index(){
        $certificates = Certificate::get();
        return view('admin.certification.index',compact('certificates'));
    }


    public function store(Request $request)
    {

        $input = $this->getData($request);
        $input['features'] = array_filter($request->features); // remove any null values in array

        Certificate::create($input);
        return redirect()->route('admin.certification.index')->with('success', 'certificate was successfully added.');
    }



    public function edit($id)
    {
        $plan = Certificate::findOrFail($id);
        return view('admin.certification.edit', compact('plan'));
    }


    public function update(Request $request, $id)
    {
        $plan = Certificate::findOrFail($id);
        $request->validate([
            'name' => 'string|min:1|max:255|required',
            'name' => ['required', 'min:1', 'max:255', 'string', Rule::unique('plans')->ignore($plan->name, 'name')],
            'amount' => 'integer|min:1|required',
            'status' => 'boolean|nullable',
            'features' => 'required|array'
        ]);


        $input = $request->all();
        $input['features'] = array_filter($request->features); // remove any null values in array
        $plan->update($input);

        return redirect()->route('admin.certification.index')->with('success', 'certificate Updated!');
    }


    protected function getData(Request $request)
    {
        $rules = [
            'name' => 'string|min:1|max:255|required|unique:plans',
            'amount' => 'integer|min:1|required',
            'status' => 'boolean|nullable',
            'features' => 'required|array'
        ];
        return $request->validate($rules);
    }

    public function destroy(Request $request){
        $plan = Certificate::findOrFail($request->id);
        $plan->delete();
        return redirect()->back();
    }


    public function getCertificates() {
        $certificates =  UserCertificate::with(['user'])->get();
        $data = [];
        // $currentDate = Carbon::now();
        
        foreach($certificates as $certificate){
            $certificate->total = $certificate->profit/23;
            // $certificate->save();
             $data[] = [
                'id'=>$certificate->id,
                'user'=>[
                    'id'=>$certificate->user->id,
                    'name'=>$certificate->user->first_name . ' '.$certificate->user->last_name ,
                    'email'=>$certificate->user->email
                ],
                 'name'=>$certificate->certificate->name,
                 'amount'=>$certificate->certificate->amount.' $',
                 'duration'=>$this->getDuration($certificate->duration),
                 'period'=>$this->getPeriod($certificate->period),
                  'start'=>$certificate->start_date,
                 'expired'=>$certificate->end_date,
                 'profit'=>number_format(($certificate->profit/23) ,2,",",".").' $',
                 'total'=>number_format($certificate->total,2,",",".").' $',
                 'expacted_profit'=>($certificate->status == 1?($certificate->duration * 12) * $certificate->profit:0) . ' $',
                 'status'=>$certificate->status_text
             ];
        }

        return view('admin.certification.requrests', compact('data'));

    }
    
    
    public function storeuser(Request $request){
        
          //  $request->validate([
        //      'duration'=>"required",
        //      'amount'=>"required",
        //      'distribution_period'=>'required',
        //      'certificates_id'=>"required|numeric|exists:certificates,id",    
        //      'user_id'=>"required|numeric|exists:users,id",    
        // ]);
        
        $certificate = Certificate::find($request->id);
        $finduser = User::find($request->user_id);
        if($finduser->balance < ($certificate->duration * $certificate->amount)){
            return redirect()->back()->with(['message'=>"your balance less than of value certificate" . $certificate->name]);
        }
        UserCertificate::create([
                'certificates_id'=>$certificate->id,
                'user_id'=>$request->user_id,
                'amount'=>$certificate->amount,
                'duration'=>$request->duration,
                'profit'=> $certificate->amount * ($certificate->average/100),
                'period'=>$request->distribution_period,
                'start_date'=>date('Y-m-d'),
                "end_date"=>date('Y-m-d',strtotime("+".$request->duration."year",strtotime(date("Y-m-d"))))
            ]);
            
            $user = User::find(1);
            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('mails.newuser', ['user' => $user], function($message)
            {
                $message
                    ->from(env('MAIL_FROM_ADDRESS'))
                    ->to(setting('admin_email','admin@cryptoassets.com'), 'Admin')
                    ->subject('New user account on '.env('APP_NAME'));
            });
        
        
        return redirect()->back()->with(['message'=>"Certificate In Process " . $certificate->name]);
    }

    public function ChangePercentageCertificated(Request $request){
        $certificate =  UserCertificate::find($request->id);
        if($certificate->percentage == 3){
            $this->setStatus(422);
            $this->setMessage("This Certificate Is Refused");
            return $this->sendApiResonse();
        }else{
            $request->validate([
                'average'=>"required|numeric",
                'id'=>"required|numeric|exists:user_certificates,id",    
            ]);
             $findCerificate = PercentageCertificates::where('certificate_id',$certificate->id)->where('status','1')->first();
             if($findCerificate){
                 $findCerificate->status ='0';
                 $findCerificate->save();
             }
             
            $certificate->history()->create([
                'total'=>$certificate->amount * ($request->average/100),
                'profit'=>$certificate->amount * ($request->average/100),
                'percentage'=>$request->average,
                'status'=>'1',
                'month'=>date('M'),
            ]);
        }
        
        
        
        $this->setStatus(200);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }
    public function ChangeStatusCertificated(Request $request){
        $certificate =  UserCertificate::find($request->id);
        $user = User::find($certificate->user_id);
        $user->load("userInfo");
        if($request->status == 3){
             $request->validate([
             'status'=>"required|in:3",
             'id'=>"required|numeric|exists:user_certificates,id",    
        ]);
             $certificate->status = $request->status;
              $certificate->save();
              $this->setStatus(200);
            $this->setMessage("Rejected Certificate " . $certificate->name);
            return $this->sendApiResonse();
        }else{
            $request->validate([
            'average'=>"required|numeric",
             'status'=>"required|in:1",
             'id'=>"required|numeric|exists:user_certificates,id",    
        ]);
        if($user->userInfo->balance < ($certificate->duration* $certificate->amount)){
            $this->setStatus(422);
            $this->setMessage("your balance less than of value certificate" . $certificate->name);
            return $this->sendApiResonse();
        }
        $certificate->status = $request->status;
        $certificate->profit = $certificate->amount * ($request->average/100);
        $certificate->total = $certificate->amount * ($request->average/100);
        $certificate->percentage = $request->average;
        $certificate->start_date = date('Y-m-d');
        $certificate->end_date = date('Y-m-d',strtotime("+".$certificate->duration."year",strtotime(date("Y-m-d"))));
        $certificate->save();
        $certificate->history()->create([
                'total'=>$certificate->amount * ($request->average/100),
                'profit'=>$certificate->amount * ($request->average/100),
                'percentage'=>$request->average,
                'status'=>'1',
                'month'=>date('M'),
            ]);
        }
        
        
        if($request->status == 1){
            
            $user = User::find($certificate->user_id);
            $user->load("userInfo");
            $user->userInfo->balance = (int)$user->userInfo->balance - (int)$certificate->certificate->amount;
            $user->userInfo->save();
        
        }else{
            $user = User::find($certificate->user_id);
        }
        $this->setStatus(200);
        $this->setMessage("success");
        return $this->sendApiResonse();
    }


    public function getDuration($value){
         return $value."year";
        // switch($value) {
        //     case '1':
        //         return "1 year";
        //         break;
        //     case '2':
        //         return "2 years";
        //         break;
        //     case '3':
        //     return "3 years";
        //             break;
        //         default:
        //         return "unknown";
        //         }
    }
    public function getPeriod($value){
        switch($value) {
            case '1':
                return "monthly";
                break;
            case '2':
                return "quarterly";
                break;
                case '3':
                    return "Semi-annually";
                    break;
                default:
                return "annually";
                }
    }
    
    
    public function getDaysCount($givenDate)
    {
        // Create a Carbon instance for the given date
        $targetDate = Carbon::createFromFormat('Y-m-d', $givenDate);
        // Get today's date using Carbon
        $today = Carbon::today();
        // Calculate the difference in days (positive for future date, negative for past date)
        $daysCount = $today->diffInDays($targetDate);

        // Return the result (you can return this in any format, here we return it as JSON)
        return  $daysCount+1;
    }


}