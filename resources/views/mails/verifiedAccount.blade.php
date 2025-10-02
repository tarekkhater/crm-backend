@extends('mails.layouts.app')
@section('main-content') 
      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr">
        <tr>
          <td style="padding: 10px;">
            <section>
              <div style="
text-align: left;
width: 90%;
margin-top: 15px;
background-color: #d1eae3;
    padding: 10px;
    border: 1px solid #d1eae3;
border-radius: 5px;
">
                <div style="color:rgb(0, 0, 0);font-size: 22px;font-weight: 400;line-height: 21.78px;">
Email verification code
</span>
                </div>
              </div>
              <main style="text-align : start; padding-left : 20px; padding-left : 20px;">
                <div class="my-8 grid grid-cols-1 gap-6" style="text-align:left">
                  <p style="color:#011610;    font-size: 22px;
    font-weight: 400;">
                    Hello <b
                      style="color:#037353;font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
                  </p>
                  <p style="margin-top : 10px;color:#011610;    font-size: 22px;
    font-weight: 500;" class="text-[#242052CC]">We have received a request to verify your quantumprime account email.<br/> Please use the following code to activate your account:
              </main>
            </section>
          </td>
        </tr>
      </table>
      <table width="100%" id="table-data" cellpadding="0" cellspacing="0" dir="ltr"
        style="justify-self: anchor-center;margin-top:10px;margin-bottom:10px;">
        <tr>
          <t style="text-align:center;">

            <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:#037353;   
padding: 10px;
;
">
              <b>{{$data['code']}}</b>
            </p>
          </td>


        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;">
        <tr>
          <td style="font-family: system-ui;color:#011610;width:100%;text-align:left;">
            <b>Get started in three easy steps:</b>
          </td>
        </tr>
      </table>

      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;margin-top:5px;">
        <tr>
              <td style="margin-left:4px;width: 13%;height: 8%;     text-align: center;">
            <img src="{{asset('Emails/lock-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:#011610;text-align:left;
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:#037353;">Enter the code </b>Go to the account verification page.
              <br />
              <span style="font-family: system-ui;color:#037353;">https://trade.quantumprime.app</span>
            </p>

          </td>
        
        </tr>

        <tr>
              <td style="margin-left:4px;width: 13%;height: 8%;     text-align: center;">
            <img src="{{asset('Emails/codes-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:#011610;text-align:left;
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:#037353;">Enter the code </b>Enter the code above.</p>

          </td>
        
        </tr>

        <tr>
<td style="margin-top:4px;width: 13%;height: 8%; text-align: center;">
            <img src="{{asset('Emails/passwords-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:#011610;text-align:left; 
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:#037353;">  Confirm your account verification</b> Follow the instructions to
              <br />
              Login.
            </p>
          </td>
          
        </tr>
      </table>

@endsection