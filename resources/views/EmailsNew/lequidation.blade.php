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
      .pic-container::before {
        position: absolute;
        content: "";
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* z-index: -1; */
      }
    </style>

    <title>Document</title>
  </head>
  <body
    style="font-family: 'Inter', sans-serif; margin: 0; box-sizing: border-box"
  >
    <div style="background-color: #f1f1f1">
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
            <img style="width: 140px" src="{{ \App\Helpers\EmailBranding::logoUrl() }}" alt="logo" />
          </div>

          <div>
            <h3
              style="
                background-color: {{ config('email-branding.colors.highlight_bg') }};
                padding: 10px 20px;
                font-size: 18px;
                border-radius: 6px;
              "
            >
              BBS USD-M Futures Liquidation Call
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
              We regret to inform you that the SOLUSDT position in your USD-M
              Futures account
              <span style="color: {{ config('email-branding.colors.primary') }}">(@yahoo.com;)</span> has been
              triggered into liquidation process as your
              <span style="font-weight: 700">10.950078 USDT</span> Margin
              Balance was below
              <span style="font-weight: 700"> 11.062411 USDT </span> maintenance
              margin required. Please note your positions might be partially
              liquidated in the case of cross margin.
            </p>
            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              At the time of liquidation, the Mark Price of SOLUSDT was
              138.280143.
            </p>
          </div>
          <div>
            <h4 style="font-weight: bold; color: #242052; font-size: 18px">
              About liquidation
            </h4>

            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              A liquidation is triggered when Margin Balance = Wallet Balance +
              Unrealized PNL &lt; Maintenance Margin, meaning the
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >Mark Price </span
              >(not Last Price) reaches the Liquidation Price and the Risk Ratio
              goes to 100%. Liquidation Price is determined by the Entry Price,
              Position Size and Margin Balance and can be calculated using our
              Calculator before opening a position. View
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >Liquidation Protocol.</span
              >
            </p>
            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              Liquidation Clear Fee is charged from you to the Insurance funds,
              which is a safety net that protect bankrupt traders from adverse
              losses and ensure that the profits of winning traders are paid out
              in full.
              <span
                style="text-decoration: underline; text-underline-offset: 4px"
                >Learn more about Insurance Fund.Visit Futures Account
              </span>
            </p>
          </div>
          <div>
            <h4 style="font-weight: bold; color: #242052; font-size: 18px">
              Risk Warning
            </h4>

            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              Risk WarningDigital asset prices can be volatile. The value of
              your investment can go down or up and you may not get back the
              amount invested. You are solely responsible for your investment
              decisions and Binance is not liable for any losses you may incur.
              Futures trading, in particular, is subject to high market risk and
              price volatility. All of your margin balance may be liquidated in
              the event of adverse price movement. Past performance is not a
              reliable predictor of future performance. Before trading, you
              should make an independent assessment of the appropriateness of
              the transaction in light of your own objectives and circumstances,
              including the risks and potential benefits. Consult your own
              advisers, where appropriate. This information should not be
              construed as financial or investment advice.
            </p>
          </div>

          <div
            class="pic-container"
            style="
              background-image: url(images/station.png);
              max-height: 266px;
              max-width: 100%;
              background-size: cover;
              background-repeat: no-repeat;
              background-position: center -15px;
              border-radius: 27px;
              color: white;
              padding: 41px 45px;
            "
          >
            <div style="display: flex; position: relative; z-index: 100">
              <div style="flex: 1">
                <p style="font-weight: 700; font-size: 20px; line-height: 29px">
                  Thanks for Choosing <br />FX Hub
                </p>
                <p
                  style="
                    font-family: Inter;
                    font-size: 14px;
                    font-weight: 500;
                    line-height: 24px;
                    text-align: left;
                  "
                >
                  We look forward to supporting you on your trading journey and
                  helping you achieve your financial goals.
                </p>
              </div>
              <div
                style="
                  flex: 1;
                  display: flex;
                  justify-content: end;
                  align-items: center;
                "
              >
                <a
                  href="#"
                  style="
                    text-decoration: none;
                    color: black;
                    background-color: white;
                    font-size: 12px;
                    font-weight: 600;
                    width: 145px;
                    padding: 8px 0;
                    text-align: center;
                    border-radius: 5px;
                  "
                  >Start Now</a
                >
              </div>
            </div>
          </div>
          <div style="margin-top: 40px">
            <p
              style="
                font-size: 18px;
                line-height: 36px;
                font-weight: 500;
                color: #242052cc;
              "
            >
              To learn more about how to protect yourself, visit our
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >Responsible Trading resource page.</span
              >
              For more information, see<span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
              >
                our Service Agreement</span
              >
              and
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >Risk Warning.</span
              >Don’t recognize this activity? Please
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >reset your password</span
              >and contact
              <span
                style="
                  color: {{ config('email-branding.colors.primary') }};
                  text-decoration: underline;
                  text-underline-offset: 4px;
                "
                >customer support </span
              >immediately.
            </p>
            <div style="text-align: center">
              <img src="images/instagram.svg" alt="instagram-icon" />
              <img src="images/twitter.svg" alt="twitter-icon" />
              <img src="images/facebook.svg" alt="facebook-icon" />
            </div>
          </div>
        </div>
        <div
          style="background-color: {{ config('email-branding.colors.primary') }}; padding: 20px 30px; color: white"
        >
          <div>
            <img style="width: 140px" src="{{ \App\Helpers\EmailBranding::logoUrl() }}" alt="logo" />
          </div>
          <p
            style="
              font-size: 10px;
              font-weight: 400;
              line-height: 12.1px;
              width: 80%;
              text-align: center;
              margin: 0 auto 10px;
            "
          >
            You are receiving this mail because you registered to join the Paradox Investing
            platform as a user or a creator. This also shows that you agree to
            our Terms of use and Privacy Policies. If you no longer want to
            receive mails from use, click the unsubscribe link below to
            unsubscribe.
          </p>
          <div
            style="
              display: flex;
              justify-content: center;
              align-items: center;
              gap: 4px;
              font-size: 10px;
            "
          >
            <span>389-623-6670</span>
            <span
              style="
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background-color: white;
                display: inline-block;
              "
            ></span>
            <span>Briana_Rohan@gmail.com</span>
            <span
              style="
                width: 4px;
                height: 4px;
                border-radius: 50%;
                background-color: white;
                display: inline-block;
              "
            ></span>

            <span>Kuhic Cliffs, cairo ,Egypt</span>
          </div>
          <p
            style="
              font-size: 8px;
              font-weight: 400;
              line-height: 24px;
              text-align: left;
            "
          >
            &copy;2023 [Your Company Name] All Rights Reserved.
          </p>
        </div>
      </div>
    </div>
  </body>
</html>
