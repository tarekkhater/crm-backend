@extends('mails.layouts.app')
@section('main-content') 
      <table width="100%" cellpadding="0" cellspacing="0" dir="rtl">
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
                    تسجيل حساب تجريبي
                  </span>
                </div>
              </div>
              <main style="text-align : start; padding-left : 20px;">
                <div class="my-8 grid grid-cols-1 gap-6" style="text-align:left">
                  <p style="color:#011610;    font-size: 22px;font-weight: 400;">
                    مرحبا <b style="color:#037353;font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">بك السيد المدير.</b>
                  </p>
                 </main>
            </section>
          </td>
        </tr>
      </table>
      <table id="table-data" cellpadding="0" cellspacing="0" dir="rtl" style="margin-bottom:10px;">
        <tr>
          <td>
               <p style="font-weight : 700;font-size: 25px;line-height: 21.78px;text-align:left;color:#037353;   
padding: 10px;
;
">
              تم ارسال طلب حساب تجريبي جديد من خلال منصه التداول الخاصه بالمستخدم يرجي الاطلاع علي هذا الطلب واليك بيانات الحساب :
              </p>
          </td>
        </tr>
      </table>
      
      <table width="100%" cellpadding="0" cellspacing="0" dir="rtl" style="justify-self: anchor-center;">
        <tr>
          <td style="text-align:left;">

            <p style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
            <ul>
               <li style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
                <b>الاسم:</b> {{$data['user']->name}}
              </li>
               <li style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
                <b>الاسم العائلي:</b> {{$data['user']->surname}}
              </li>
              <li style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
                <b>البريد الالكترني:</b> {{$data['user']->email}}
              </li>
              <li style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
                <b>الهاتف:</b> {{$data['user']->phone}}
              </li>
               <li style="font-weight : 700;font-size: 22px;text-align:left;color:#037353;   padding: 0px 10px 10px;">
                <b>العنوان:</b> {{$data['user']->address}}
              </li>

            </ul>
            </p>
          </td>


        </tr>
      </table>
      

@endsection