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
                    {{$data['title']}}
                  </span>
                </div>
              </div>
              <main style="text-align : start; padding-left : 20px;">
                <div class="my-8 grid grid-cols-1 gap-6" style="text-align:left">
                  <p style="color:#011610;    font-size: 22px;font-weight: 400;">
                    Hello <b style="color:#037353;font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
                  </p>
                 </main>
            </section>
          </td>
        </tr>
      </table>
      <table width="100%" id="table-data" cellpadding="0" cellspacing="0" dir="ltr" style="width:100%;margin-bottom:10px;">
        <tr>
          <td>
              {!!$data['content']!!}
          </td>
        </tr>
      </table>
      

@endsection