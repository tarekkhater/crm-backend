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
            <img style="width: 140px" src="images/logo.png" alt="logo" />
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
              We are pleased to confirm that your new trade has been
              successfully initiated on FX Hub. Below are the details of your
              trade:
            </p>
            
            <div
              style="
                background-color: #f6f2ff;
                border-radius: 10px;
                width: 370px;
                margin: 0 auto 20px;
              "
            >
              <div style="padding: 25px 25px 0">
                <div
                  style="
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                  "
                >
                  <div
                    style="
                      font-size: 12px;
                      font-weight: 700;
                      line-height: 24px;
                      display: flex;
                      justify-content: center;
                      align-items: center;
                      gap: 12px;
                    "
                  >
                    <img src="images/asset.svg" alt="asset-icon" /> Buy TNX INC
                  </div>
                  <div
                    style="
                      font-size: 10px;
                      font-weight: 700;
                      line-height: 15px;
                      color: #898989;
                      display: flex;
                      flex-direction: column;
                    "
                  >
                    <span>#4657578857</span>
                    <span>Trade ID</span>
                  </div>
                </div>
                <div style="margin-top: 20px">
                  <div style="display: flex; align-items: center; gap: 25px">
                    <span style="font-size: 20px; font-weight: 700"
                      >160.98
                    </span>
                    <span
                      style="
                        font-size: 8px;
                        font-weight: 700;
                        line-height: 24px;
                        color: #3bb54a;
                      "
                      >1.8 (0.4%)</span
                    >
                  </div>
                  <div
                    style="
                      margin-top: 5px;
                      display: flex;
                      flex-direction: column;
                      gap: 5px;
                    "
                  >
                    <div
                      style="font-size: 10px; font-weight: 700; color: #898989"
                    >
                      Opened At: 14 May 2013
                    </div>
                    <div
                      style="font-size: 10px; font-weight: 700; color: #898989"
                    >
                      Leverage: 13
                    </div>
                  </div>
                </div>
              </div>
              <div style="padding-bottom: 30px">
                <img src="images/chart.png" style="max-width: 100%" alt="" />
              </div>
            </div>
            <div style="color: #242052cc; line-height: 36px; font-weight: 500;">
              <p>
                You can view more details and track the progress of your trade
                by logging into your account.
              </p>
            </div>
            <div style="text-align: center; margin: 30px 0">
              <a
                href="#"
                style="
                  text-decoration: none;
                  display: inline-block;
                  width: 150px;
                  text-align: center;
                  background-color: #0070e4;
                  color: white;
                  padding: 12px 0;
                  font-size: 12px;
                  border-radius: 6px;
                "
              >
                Open Trades</a
              >
            </div>
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
          <div style="text-align: center; margin-top: 40px;">
            <img src="images/instagram.svg" alt="instagram-icon" />
            <img src="images/twitter.svg" alt="twitter-icon" />
            <img src="images/facebook.svg" alt="facebook-icon" />
          </div>
        </div>
        <div
          style="background-color: #007aff; padding: 20px 30px; color: white"
        >
          <div>
            <img style="width: 140px" src="images/logo.png" alt="logo" />
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
            You are receiving this mail because you registered to join the CIRCO
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
