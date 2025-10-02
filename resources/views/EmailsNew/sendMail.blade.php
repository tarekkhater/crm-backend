     
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
          {{$data['subject']}}
          </span>
        </div>
      </div>
      <main style="text-align : start; padding-left : 20px; padding-right : 20px;">
        <div class="my-8 grid grid-cols-1 gap-6">
          <p class="text-[#242052CC]">
            Hi <b style="font-weight : 700;font-size: 18px;line-height: 21.78px;font-family: system-ui;">{{$data['user']->name}} {{$data['user']->surname}}.</b>
          </p>
          <p style="margin-top : 10px;" class="text-[#242052CC]">
            you received a request to  your quantumprime account:
            <p style="font-weight : 700;font-size: 37px;line-height: 21.78px;text-align:center">
                <b>{!!html_entity_decode($data['message'])!!}</b>
            </p>
          </p>
        </div>
      </main>
      </section>
@endsection