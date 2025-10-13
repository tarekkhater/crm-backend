     
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta
      name="viewport"
      content="width=
    , initial-scale=1.0"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
      rel="stylesheet"
    />
    <style>
      .pic-container {
        position: relative;
        overflow: hidden;
      }
      .start_button{
          flex: 1;
                  display: flex;
                  justify-content: end;
                  align-items: center!important;
      }
      .pic-container::before {
        position: absolute!importanti;
        content: "";
        top: 0;
        left: 0;
        width: 100%!importanti;
        height: 100%!importanti;
        background-color: rgba(0, 0, 0, 0.5)!importanti;
        /* z-index: -1; */
      }
    </style>

    <title>Contact information</title>
  </head>
  <body
    style="font-family: 'Inter', sans-serif; margin: 0; box-sizing: border-box"
  >
    <div style="background-color: #000">
      <div
        style="
          width: 640px;
          height: 100%;
          background-color: white;
          margin: 0 auto;
        "
      >
        <div style="padding: 50px">
          <div>
            <img style="width: 140px" src="{{asset('images/logoBPS.png')}}" alt="logo" />
          </div>

          <div>
            <h3
              style="
                background-color: #f0e9ff;
                padding: 10px 20px;
                font-size: 18px;
                border-radius: 6px;
              "
            >
              Contact information
            </h3>
          </div>

          <div>
            <h4 style="font-weight: 400; color: #242052; font-size: 18px">
              Dear <span style="font-weight: bold"> Hassan. </span>
            </h4>

            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              
            We are pleased to inform you.
            A customer's information has been sent to inquire about something. 
            Below is the contact information:
            </p>
          </div>
          <div style="color: #242052cc; line-height: 36px">
            <h3 style="font-size: 18px">contact information</h3>
            <div>
              <ul>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Name:</span
                  >
                  {{$data->fullName}}
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Email:</span
                  >
                  {{$data->email}}
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Phone:</span
                  >
                 {{$data->phone}}
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Subject:</span
                  >
                  {{$data->subject}}
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Message:</span
                  >
               {{$data->message}}
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Plan:</span
                  >
               {{$data->plan}}
                </li>
              </ul>
            </div>
           
          </div>
          

        
           
        </div>
        </div>
      </div>
    </div>
  </body>
</html>
