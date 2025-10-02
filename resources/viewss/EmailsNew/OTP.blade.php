     
        @extends('EmailsNew.layouts.app')
        @section('main-content') 
          <section>
          <div style="
      text-align: left;
    width: 90%;
    margin-top: 15px;
    background-color: #0BC691;
    padding: 10px;
    border: 1px solid #0BC691;
    border-radius: 5px;
      ">
        <div style="color:white;font-size: 18px;font-weight: 400;line-height: 21.78px;">
          Account verification
          </span>
        </div>
      </div>
      <main style="text-align : start; padding-left : 20px; padding-right : 20px;">
        <div class="my-8 grid grid-cols-1 gap-6">
          <p style="color:#000;">
            Hi <b style="color:#0BC691;font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
          </p>
          <p style="margin-top : 10px;color:#000;" class="text-[#242052CC]">
            We received a request to Verified your Account for your alwaaialmali account. Please use the following code to Verified your Account:
            <div style="width:100%;justify-content: center!important;display:inline-flex;">
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[0]}}</b>
                </p>
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[1]}}</b>
                </p>
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[2]}}</b>
                </p>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[3]}}</b>
                </p>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[4]}}</b>
                </p>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#0BC691;    border: 3px solid #0bc691;
    padding: 10px;
    border-radius: 5px;
">
                  <b>{{str_split($data['code'])[5]}}</b>
                </p>
            </div>
          </p>
        </div>
        <div class="my-12">
          <b style="font-family: system-ui;color:#000;">Get Started in Three Easy Steps:</b>
          <div class="gird gird-cols-1 mt-2">
            <!--<div class="flex gaps-4 gap-4">-->
            <!--  <img src="{{asset('Emails/lock.png')}}" alt="illustration-1" />-->
            <!--  <p style="align-content:center"><b style="font-family: system-ui;">Reset Password </b>Go to the Password Reset Page.</p>-->
            <!--</div>-->
            <div class="gap-4 my-6" style="display:inline-flex;margin-top:5px;align-items: baseline;align-items: anchor-center;">
              <img src="https://backend.bbstechnology.net/Emails/code-svg.png" style="margin-right:4px;width: 9%;height: 8%;" alt="illustration-1" />
              <p style="align-content:center!important;color:#000;"><b style="font-family: system-ui;color:#0BC691;">Enter the Code </b>Enter the code provided above.</p>
            </div>
            <!--<div class=" gap-4" style="display:inline-flex;margin-top:5px;align-items: baseline;align-items: anchor-center;">-->
            <!--  <img src="https://backend.bbstechnology.net/Emails/password-svg.png" style="margin-right:4px;width: 9%;height: 8%" alt="illustration-1" />-->
            <!--  <p style="align-content:center!important;color:#000;"><b style="font-family: system-ui;color:#0BC691;">Create a New Password</b> Follow the instructions to set a new password.</p>-->
            <!--</div>-->
          </div>
        </div>
      </main>
      </section>
@endsection