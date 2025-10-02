     
        @extends('EmailsNew.layouts.app')
        @section('main-content') 
          <section>
          <div style="
      text-align: left;
    width: 100%;
    margin-top: 15px;
    background-color: #F0E9FF;
    padding: 10px;
    border: 1px solid #F0E9FF;
    border-radius: 5px;
      ">
        <div style="    font-size: 18px;font-weight: 400;line-height: 21.78px;">
          Code Forget Password
          </span>
        </div>
      </div>
      <main style="text-align : start; padding-left : 20px; padding-right : 20px;">
        <div class="my-8 grid grid-cols-1 gap-6">
          <p class="text-[#242052CC]">
            Hi <b style="font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
          </p>
          <p style="margin-top : 10px;" class="text-[#242052CC]">
            We received a request to reset your password for your alwaaialmali account. Please use the following code to reset your password:
            <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center">
                <b>{{$data['code']}}</b>
            </p>
          </p>
        </div>
        <div class="my-12">
          <b style="font-family: system-ui;">Get Started in Three Easy Steps:</b>
          <div class="gird gird-cols-1 mt-2">
            <div class="flex gaps-4 gap-4">
              <img src="{{asset('Emails/lock.png')}}" alt="illustration-1" />
              <p style="align-content:center"><b style="font-family: system-ui;">Reset Password </b>Go to the Password Reset Page.</p>
            </div>
            <div class="flex gap-4 my-6">
              <img src="{{asset('Emails/code.png')}}" alt="illustration-1" />
              <p style="align-content:center"><b style="font-family: system-ui;">Enter the Code </b>Enter the code provided above.</p>
            </div>
            <div class="flex gap-4">
              <img src="{{asset('Emails/password.png')}}" alt="illustration-1" />
              <p style="align-content:center"><b style="font-family: system-ui;">Create a New Password</b> Follow the instructions to set a new password.</p>
            </div>
          </div>
        </div>
      </main>
      </section>
@endsection