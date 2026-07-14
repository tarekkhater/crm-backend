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
background-color: {{ config('email-branding.colors.highlight_bg') }};
    padding: 10px;
    border: 1px solid {{ config('email-branding.colors.highlight_bg') }};
border-radius: 5px;
">
                <div style="color:rgb(0, 0, 0);font-size: 22px;font-weight: 400;line-height: 21.78px;">
                 Kod resetowania hasła
                  </span>
                </div>
              </div>
              <main style="text-align : start; padding-left : 20px; padding-left : 20px;">
                <div class="my-8 grid grid-cols-1 gap-6" style="text-align:left">
                  <p style="color:{{ config('email-branding.colors.text') }};    font-size: 22px;
    font-weight: 400;">
                    Witaj <b
                      style="color:{{ config('email-branding.colors.accent') }};font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
                  </p>
                  <p style="margin-top : 10px;color:{{ config('email-branding.colors.text') }};    font-size: 22px;
    font-weight: 500;" class="text-[#242052CC]">Otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta Paradox Investing.<br/> Skorzystaj z poniższego kodu, aby ustawić nowe hasło:
              </main>
            </section>
          </td>
        </tr>
      </table>
      <table width="100%" id="table-data" cellpadding="0" cellspacing="0" dir="ltr"
        style="justify-self: anchor-center;margin-top:10px;margin-bottom:10px;">
        <tr>
          <t style="text-align:center;">

            <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center;color:{{ config('email-branding.colors.accent') }};   
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
          <td style="font-family: system-ui;color:{{ config('email-branding.colors.text') }};width:100%;text-align:left;">
            <b>Rozpocznij w trzech prostych krokach:</b>
          </td>
        </tr>
      </table>

      <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="justify-self: anchor-center;margin-top:5px;">
        <tr>
             <td style="margin-left:4px;width: 13%;height: 8%;     text-align: center;">
            <img src="{{asset('Emails/lock-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:{{ config('email-branding.colors.text') }};text-align:left;
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Wprowadź kod </b> i przejdź na stronę resetowania hasła.
              <br />
              <span style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">trade.Paradox Investing.app</span>
            </p>

          </td>
         
        </tr>

        <tr>
            <td style="margin-left:4px;width: 13%;height: 8%;     text-align: center;">
            <img src="{{asset('Emails/codes-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:{{ config('email-branding.colors.text') }};text-align:left;
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Wprowadź kod </b> podany powyżej.</p>

          </td>
          
        </tr>

        <tr>
 <td style="margin-top:4px;width: 13%;height: 8%; text-align: center;">
            <img src="{{asset('Emails/passwords-svg.png')}}"
              style="margin-left:4px;width: 55%;height: 80%;" alt="illustration-1" />
          </td>
          <td style="align-content:center!important;color:{{ config('email-branding.colors.text') }};text-align:left; 
            font-size: 17px;direction:ltr">
            <p style="    font-size: 16px;
    font-weight: 700;
    line-height: 26px;"><b style="font-family: system-ui;color:{{ config('email-branding.colors.accent') }};">Utwórz nowe hasło</b> i postępuj zgodnie z instrukcjami, aby
              <br />
              ustawić nowe hasło.
            </p>
          </td>
         
        </tr>
      </table>

@endsection
