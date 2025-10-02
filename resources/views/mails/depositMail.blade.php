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
                  Deposit the account
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
    font-weight<p style="margin-top : 10px;color:#011610;    font-size: 22px;
    font-weight: 500;" class="text-[#242052CC]">
                  We are pleased to inform you that your last deposit has been successfully processed. Here are the details of your transaction:

                  </p>
                
              </main>
            </section>
          </td>
        </tr>
      </table>
   
 <table width="100%" id="table-data" cellpadding="0" cellspacing="0" dir="ltr"
        style="width:100%;justify-self: anchor-center;margin-top:10px;padding:40px;margin-bottom:10px;border-top: 1px solid #d1eae3;;border-bottom: 1px solid #d1eae3;">
        <tr>
          <td style="text-align:center;">
            <img src="{{asset('Emails/success.png')}}"
              style="margin-left:4px;width: 10%;height: 80%;" alt="illustration-1" />
            <p style="font-weight : 700;font-size: 28px;text-align:center;color:#037353;    margin: 0px;">
             Deposit details
            </p>
            <p style="font-weight : 700;font-size: 37px;text-align:center;color:#037353;    margin: 5px 0px;">
              {{$data['amount']}}$
            </p>
            <p style="font-weight : 700;font-size: 14px;text-align:center;color:#696b6a;    margin: 0px;">
              {{$data['date']}}</p>
          </td>


        </tr>
      </table>
      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;">
        <tr>
          <td style="font-family: system-ui;color:#011610;width:100%;text-align:left;">
            <b>Account balance</b>
          </td>
        </tr>
        <tr>
          <td style="text-align:left;">

            <p style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
            <ul>
             <li style="font-weight: 700; font-size: 22px; text-align: left; color: #037353; padding: 0px 10px 10px;">
  <b>Previous Balance:</b> {{$data['money']}}
</li>
<li style="font-weight: 700; font-size: 22px; text-align: left; color: #037353; padding: 0px 10px 10px;">
  <b>Current Balance:</b> {{$data['total']}}
</li>

            </ul>
            </p>
          </td>


        </tr>
      </table>

      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;margin-top:5px;">

        <tr>
          <td style="align-content:center!important;color:#011610;text-align:left;
            font-size: 20px;direction:ltr">
            <p style="    font-size: 20px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:#037353;">
              Your deposits are now available in your account, and you can start trading immediately. To view your account balance and transaction history, please log in to your account.</b>
            </p>

          </td>

        </tr>




      </table>

@endsection