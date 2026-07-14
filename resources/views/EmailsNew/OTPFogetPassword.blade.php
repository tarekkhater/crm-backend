
        @extends('EmailsNew.layouts.app')
        @section('main-content') 
        <table width="100%" cellpadding="0" cellspacing="0" dir="ltr">
        <tr>
            <td style="padding: 10px;">
     <section>
          <div style="
      text-align: left;
    width: 90%;
    margin-top: 15px;
    background-color: {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border: 1px solid {{ config('email-branding.colors.accent') }};
    border-radius: 5px;
      ">
        <div style="color:white;font-size: 18px;font-weight: 400;line-height: 21.78px;">
          Code Forget Password
          </span>
        </div>
      </div>
      <main style="text-align : start; padding-left : 20px; padding-right : 20px;">
        <div class="my-8 grid grid-cols-1 gap-6" style="text-align:left">
          <p style="color:#000;">
            Hi <b style="color:{{ config('email-branding.colors.accent') }};font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
          </p>
          <p style="margin-top : 10px;color:#000;" class="text-[#242052CC]">
           We received a request to reset your password for your Paradox Investing account. Please use the following code to reset your password:
             </main>
      </section>
            </td>
  </tr>
</table>
              <table id="table-data" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;margin-top:10px;margin-bottom:10px;">
                <tr>
                <td>
            
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[0]}}</b>
                </p>
                </td>
                <td>
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[1]}}</b>
                </p>
                </td>
                <td>
                <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[2]}}</b>
                </p>
                </td>
                <td>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[3]}}</b>
                </p>
                </td>
                <td>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;margin-right:3px;
">
                  <b>{{str_split($data['code'])[4]}}</b>
                </p>
                </td>
                <td>
                 <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};    border: 3px solid {{ config('email-branding.colors.accent') }};
    padding: 10px;
    border-radius: 5px;
">
                  <b>{{str_split($data['code'])[5]}}</b>
                </p>
                </td>
           
     
  </tr>
</table>
            <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;">
                <tr>
                    <td  style="font-family: system-ui;color:#000;width:100%;text-align:left;">
                        <b>Get Started in Two Easy Steps:</b>
                    </td>
                </tr>
            </table>
            
            <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;margin-top:5px;">
                <!--<tr>-->
                <!--      <td style="margin-right:4px;width: 9%;height: 8%;">-->
                <!--          <img src="{{asset('Emails/lock.png')}}" style="margin-right:4px;width: 60%;height: 60%;"  alt="illustration-1" />-->
                <!--      </td>-->
                <!--      <td style="align-content:center!important;color:#000;text-align:left">-->
                <!--          <p><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Reset Password </b>Go to the Password Reset Page.</p>-->
                <!--      </td>-->
                <!--</tr>-->
                <tr>
                  <td style="margin-right:4px;width: 9%;height: 8%;     text-align: justify;">
                      <img src="https://backend.bbstechnology.net/Emails/code-svg.png" style="margin-right:4px;width: 60%;height: 60%;"  alt="illustration-1" />
                  </td>
                  <td style="align-content:center!important;color:#000;text-align:left;    position: absolute;
                    left: 8%;
                    bottom: 41%;
                    font-size: 17px;">
                      <p><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Enter the Code </b>Enter the code provided above.</p>
                  </td>
                </tr>
                
                <tr>
                  <td style="margin-top:4px;width: 9%;height: 8%;">
                      <img src="https://backend.bbstechnology.net/Emails/password-svg.png" style="margin-right:4px;width: 60%;height: 60%;"  alt="illustration-1" />
                  </td>
                  <td style="align-content:center!important;color:#000;text-align:left;    position: absolute;
                    left: 8%;
                    bottom: 41%;
                    font-size: 17px;">
                      <p><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Create a New Password</b> Follow the instructions to set a new password.</p>
                  </td>
                </tr>
            </table>
@endsection