     
        @extends('emails.layouts.app')
        @section('main-content') 
            <h3
              style="
                background-color: {{ config('email-branding.colors.highlight_bg') }};
                padding: 10px 20px;
                font-size: 18px;
                border-radius: 6px;
              "
            >
              Account Withdrawals
            </h3>
          </div>

          <div>
            <h4 style="font-weight: 400; color: #242052; font-size: 18px">
              Dear <span style="font-weight: bold"> Mohamed. </span>
            </h4>

            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              We’re thrilled to have you on board. Your account has been
              successfully created, and you are now ready to start trading.
              Below are your account credentials:
            </p>
            <!-- <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              At the time of liquidation, the Mark Price of SOLUSDT was
              138.280143.
            </p> -->
          </div>
          <div style="color: #242052cc; line-height: 36px">
            <h3 style="font-size: 18px">Account Balance</h3>
            <div>
              <ul>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Username:</span
                  >
                  [Your Username]
                </li>
                <li style="font-weight: 500">
                  <span style="font-weight: 700; color: #000000cc"
                    >Password:</span
                  >
                  [Your Password]
                </li>
              </ul>
            </div>
            <p>
              For security reasons, we recommend changing your password after
              your first login.
            </p>
          </div>
          <div
            style="
              display: flex;
              flex-direction: column;
              justify-content: center;
              align-items: center;
              margin: 40px 0;
              gap: 15px;
            "
          >
            <div>
              <p style="font-size: 18px; font-weight: 700">
                Get Started in Three Easy Steps:
              </p>
              <div
                style="
                  display: flex;
                  align-items: center;
                  gap: 15px;
                  margin-bottom: 15px;
                "
              >
                <div><img src="https://demo.fx-hub.net/images/login-icon.png" alt="icon" /></div>
                <div>
                  <p>
                    <span style="font-size: 18px; font-weight: 700"
                      >Log In
                    </span>
                    Access your account using your email and the password you
                    created during registration.
                  </p>
                </div>
              </div>
              <div
                style="
                  display: flex;
                  align-items: center;
                  gap: 15px;
                  margin-bottom: 15px;
                "
              >
                <div><img src="https://demo.fx-hub.net/images/fund-icon.png" alt="icon" /></div>
                <div>
                  <p>
                    <span style="font-size: 18px; font-weight: 700"
                      >Fund Your Account
                    </span>
                    Deposit funds quickly and securely through a variety of
                    payment methods.
                  </p>
                </div>
              </div>
              <div style="display: flex; align-items: center; gap: 15px">
                <div><img src="https://demo.fx-hub.net/images/trading-icon.png" alt="icon" /></div>
                <div>
                  <p>
                    <span style="font-size: 18px; font-weight: 700"
                      >Start Trading </span
                    >Explore our platform, choose your instruments, and begin
                    trading.
                  </p>
                </div>
              </div>
            </div>
          </div>
@endsection