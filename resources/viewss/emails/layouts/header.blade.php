<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Language" content="en">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
  <title>quantumprime</title>
  <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

  <style>
    .footertext {
      font-size: 12px;
    }

    @media (min-width: 640px) {
      .footertext {
        font-size: 16px;
      }
    /* Default styles (optional) */
#table-data {
  width: 50%; /* Default width */
  height: auto; /* Default height */
}

/* For screen widths between 768px and 1024px */
@media (min-width: 768px) and (max-width: 1024px) { 
  #table-data {
    width: 50%;  /* Adjust width for tablets or medium-sized screens */
  }
}

/* For screens with a max width of 768px (e.g., tablets or large mobile) */
@media (max-width: 768px) {
  #table-data {
    width: 100%;  /* Adjust width for smaller devices */
  }
}

/* For screens with a max width of 480px (e.g., small mobile) */
@media (max-width: 480px) {
  #table-data {
    height: 100px;  /* Adjust height for very small screens */
  }
}

  </style>
</head>

<body style="margin : 0px">
  <div
    style="align-items: center; justify-content: center; flex-direction: column; margin-top: 1.25rem; font-family : Nunito, sans-serif " dir="ltr">
    <section style="max-width: 42rem; background-color: #fff;    justify-self: anchor-center;">
        <table width="100%" cellpadding="0" cellspacing="0" dir="ltr" style="">
            <tr>
                <td style="width: 100%;        text-align: center;">
                    <a href="#">
                      <img src="{{asset('Emails/logo-light.png')}}" alt="tailwindtaplogo" style="width:220px;justify-content: center;" />
                    </a>
                </td>
            </tr>
        </table>