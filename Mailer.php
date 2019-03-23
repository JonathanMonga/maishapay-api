<?php

return;
use PHPMailer\PHPMailer\PHPMailer;

require_once '../PHPMailer/src/Exception.php';
require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';

class Mailer
{
    static function contact($subject, $body, $replyTo = "david@maishapay.online", $replayToAlias='David Kazad', $to = 'contact@maishapay.online', $alias = 'Maishapay utilsateur', $from = 'maishapay.online@gmail.com', $password = 'Landry@22'){
        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->addReplyTo($replyTo, $replayToAlias);
        $mail->setFrom($from, $alias);
        $mail->addAddress($to, $alias);
        $mail->Subject = $subject;

        $mail->msgHTML($body);

        if (!$mail->send()) {

            return array('resultat' => 1, 'message' => $mail->ErrorInfo);

        } else {

            return array('resultat' => 2, 'message' => 'email sent!');

        }
    }

    static function sendmail($subject, $body, $to, $alias = 'Maishapay Developer', $from = 'maishapay.online@gmail.com', $password = 'Landry@22')
    {
        $mail = new PHPMailer;
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $from;
        $mail->Password = $password;
        $mail->addReplyTo("david@maishapay.online", "David Kazad");
        $mail->setFrom($from, $alias);
        $mail->addAddress($to, $alias);
        $mail->Subject = $subject;

        $mail->msgHTML($body);

        if (!$mail->send()) {

            return array('resultat' => 1, 'message' => $mail->ErrorInfo);

        } else {

            return array('resultat' => 2, 'message' => 'email sent!');

        }
    }

    static function pin_pedu_template($name, $code)
    {
        return '
            <!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Maishapay Message</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      
      /*All the styling goes here*/
      
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; 
      }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; 
      }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; 
      }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; 
      }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        Margin: 0 auto !important;
        /* makes it centered */
        max-width: 580px;
        padding: 10px;
        width: 580px; 
      }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
        max-width: 580px;
        padding: 10px; 
      }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; 
      }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; 
      }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        Margin-top: 10px;
        text-align: center;
        width: 100%; 
      }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; 
      }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        margin-bottom: 30px; 
      }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; 
      }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 14px;
        font-weight: normal;
        margin: 0;
        margin-bottom: 15px; 
      }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; 
      }

      a {
        color: #3498db;
        text-decoration: underline; 
      }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; 
      }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; 
      }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; 
      }

      .btn-primary table td {
        background-color: #3498db; 
      }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; 
      }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; 
      }

      .first {
        margin-top: 0; 
      }

      .align-center {
        text-align: center; 
      }

      .align-right {
        text-align: right; 
      }

      .align-left {
        text-align: left; 
      }

      .clear {
        clear: both; 
      }

      .mt0 {
        margin-top: 0; 
      }

      .mb0 {
        margin-bottom: 0; 
      }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; 
      }

      .powered-by a {
        text-decoration: none; 
      }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; 
      }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; 
        }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; 
        }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; 
        }
        table[class=body] .content {
          padding: 0 !important; 
        }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; 
        }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; 
        }
        table[class=body] .btn table {
          width: 100% !important; 
        }
        table[class=body] .btn a {
          width: 100% !important; 
        }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; 
        }
      }

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; 
        }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; 
        }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; 
        }
        .btn-primary table td:hover {
          background-color: #34495e !important; 
        }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; 
        } 
      }

    </style>
  </head>
  <body class="">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
           
            <table role="presentation" class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                  
                  <tr>
                          <img src="https://i.imgur.com/FPlte0B.png">
                    </tr>
                    <tr>
                    
                      <td>
                        <p>Bonjour ' . $name . ',</p>
                        <p> Votre code pour Reinitialiser votre mot de passe. est</p>
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="btn btn-primary">
                          <tbody>
                            <tr>
                              <td align="left">
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td> <a href="http://maishapay.online" target="_blank">' . $code . '</a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <p>L\'Equipe de maishapay.</p>
                        <p>Good luck! Hope it works.</p>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>

            <!-- START FOOTER -->
            <div class="footer">
              <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="content-block">
                    <span class="apple-link">Maishapay Inc, 243 Kanbove Road, Lubumbashi CA 00243</span>
                    <br> Don\'t like these emails? <a href="http://maishapay.online/unsubscribe">Unsubscribe</a>.
                  </td>
                </tr>
                <tr>
                  <td class="content-block powered-by">
                    Powered by <a href="http://maishapay.online">Maishapay</a>.
                  </td>
                </tr>
              </table>
            </div>
            <!-- END FOOTER -->

          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
        ';
    }

    static function verifyEmailTemplate($AccountId, $confirmationCode)
    {
        $template = '<div class="text-center">

    <table class="m_5281187580119612131body-table" style="table-layout:fixed" width="100%" cellspacing="0"
           cellpadding="0" border="0">
        <tbody>

        <tr>
            <td valign="top" align="center">

                <table style="background-color:#22af43;color:#ffffff;table-layout:fixed" width="100%" cellspacing="0"
                       cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td class="m_5281187580119612131header-col" style="padding-bottom:32px;padding-top:32px"
                            valign="middle" align="center">
                            <img width="160" height="80" 
                            src="https://i.imgur.com/FPlte0B.png"
                                 alt="Maishapay Logo" style="height:50px;vertical-align:middle" class="CToWUd">
                            <h1 style="color:white;display:inline-block;font-size:20px;font-weight:300;line-height:1.2em;padding-left:20px;max-width:400px;text-align:left;vertical-align:middle;word-wrap:break-word">
                                Verify Your Email</h1>
                        </td>
                    </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
            <td style="background-color:#ffffff" valign="top" align="center">

                <table style="table-layout:fixed" width="85%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="padding-top:30px" valign="top" align="center">Thank you for creating a Maishapay developer
                            Account.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:20px;padding-top:20px" valign="top" align="center">Verify your email
                            below to complete your setup.
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:20px" align="center">
                         <a href="https://Maishapay.online/webapp/?api_key=' . $AccountId . '"
                               class="m_5281187580119612131guid"
                               style="color:#22af43;font-weight:bold;text-decoration:none" target="_blank"
                               data-saferedirecturl="https://www.maishapay.online/?api_key=' . $AccountId . '&pass=">' . $AccountId . '</a>
                            <a class="m_5281187580119612131button"
                               href="https://Maishapay.online/?api_key=' . $AccountId . '"
                               style="background:#10ade4;border-radius:20px;color:#ffffff;display:inline-block;font-size:13px;margin-right:10px;padding-bottom:10px;padding-left:30px;padding-right:30px;padding-top:10px;text-align:center;text-decoration:none;text-transform:uppercase"
                               target="_blank"
                               data-saferedirecturl="https://www.Maishapay.com/?api_key=' . $AccountId . '">Click to confirm your account</a>
                            <hr style="background-color:#d8d8d8;border:none;height:1px;margin-top:30px">
                        </td>
                    </tr>

                    <tr>
                        <td align="center">
                            <b>Your API_KEY :&nbsp;</b>
                            <a href="https://Maishapay.info/Account/#/login/338b30bf-8fc5-4ced-862c-f019d7650c28"
                               class="m_5281187580119612131guid"
                               style="color:#22af43;font-weight:bold;text-decoration:none" target="_blank"
                               data-saferedirecturl="https://www.maishapay.online/?api_key=' . $AccountId . '">' . $AccountId . '</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom:25px;padding-top:10px" align="center">Use your unique Account ID to log
                            into your Maishapay Account.
                        </td>
                    </tr>
                    </tbody>
                </table>

            </td>
        </tr>
        <tr>
            <td valign="top" align="left">
                <table style="table-layout:fixed" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td align="center">
                            <table class="m_5281187580119612131mobile-apps-col"
                                   style="display:none;padding-top:30px;table-layout:fixed" width="100%" cellspacing="0"
                                   cellpadding="0" border="0">
                                <tbody>
                                <tr>
                                    <td style="width:50%" align="center">
                                        <a href="https://itunes.apple.com/us/app/Maishapay-bitcoin-Account/id493253309"
                                           style="text-decoration:none" target="_blank"
                                            <img alt="Download on the App Store"
                                                 src="https://Maishapay.info/Resources/apple-badge@2x.png"
                                                 style="height:40px;vertical-align:middle" class="CToWUd">
                                        </a>
                                    </td>
                                    <td style="width:50%" align="center">
                                        <a href="https://play.google.com/store/apps/details?id=piuk.Maishapay.android"
                                           style="text-decoration:none" target="_blank">
                                            <img alt="GET IT ON Google Play"
                                                 src="https://ci5.googleusercontent.com/proxy/HzvlemUmLEz_TJB0dDniUWABAJkn917bsKelNPe0j9Vg5A2tKkMIsbgLcaFHoANtyf8ug7KEcnTVQaT96_7Bhz4Q-jCbRDIC3TdksBq5Xew=s0-d-e1-ft#https://Maishapay.info/Resources/google-play-badge@2x.png"
                                                 style="height:40px;vertical-align:middle" class="CToWUd">
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" align="center">

                            <table class="m_5281187580119612131footer-table" style="padding-top:25px;table-layout:fixed"
                                   width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tbody>
                                <tr>
                                    <td style="width:20%" align="left">
                                        <a href="https://www.Maishapay.com/" style="text-decoration:none"
                                           target="_blank"
                                            <img alt="Blockchain"
                                                 src="https://lh3.googleusercontent.com/MPDwjmLnVEx9GmoxW_vfYO4gpHsuZdgkQzyLyduWjxptryUW54RkxkBQn2VmrijIE6XSU5uY7ylmo9QOc4Te1iyKhS9Y3BqILWURQj9jlU_dJSHgq1XMjGbsd0sv6c3LmJQYR_2A0pTsHYoeA9eiDayxHitcLep8IFXJ-Yrkib9pOzCQbCwl970REvfzpIWjzX87lwq2MxTEUcK9WnfYLH63SDhqV10-gEwBFry1STyJUIbRdLczwHkKw94B1prOwNsGjaLEYjjtCSs9saujJpdhoLvRb_jqh3Chi33CNo8k1s5ghP75sk_1yDuesLlLFClpfX0fmM90c7IcthoVOw81AW9BHMtMRMb5dsjerLHBOm2xm9dkGH7wCG3vtpo5Yo2DCCyIZxl4uUMWxFRNiocYMJB0XMX_XAxRyAOEVpXackvv05kJqQr8Kz2cKV8mQOxopKBrutjgIQ_3sjEg_v0xOtqiEIZxf4ncvHppeKPbjXa6wtpj6GXTHyEEpXq25aWUSMWxinU0tBV4zR3PkBUZUryGprBMaLPQOgnlTMbE9NHOQXpNHO60AfWGK8T7Ouf7Oeq2zTSuCBTmtIl362BDKsz3Bvl7u0zQmR4=s104-no"
                                                 style="height:35px;vertical-align:middle" class="CToWUd">
                                        </a>
                                    </td>
                                    <td style="width:40%" align="center">
                                        <a href="https://itunes.apple.com/us/app/Maishapay-bitcoin-Account/id493253309"
                                           style="text-decoration:none" target="_blank"
                                           data-saferedirecturl="https://itunes.apple.com/us/app/Maishapay">
                                            <img alt="Download on the App Store"
                                                 src="https://blockchain.info/Resources/apple-badge@2x.png"
                                                 style="height:35px;margin-right:5px;vertical-align:middle"
                                                 class="CToWUd">
                                        </a>
                                        <a href="https://play.google.com/store/apps/details?id=piuk.Maishapay.android"
                                           style="text-decoration:none" target="_blank">
                                            <img alt="GET IT ON Google Play"
                                                 src="https://blockchain.info/Resources/google-play-badge@2x.png"
                                                 style="height:35px;margin-left:5px;vertical-align:middle"
                                                 class="CToWUd">
                                        </a>
                                    </td>
                                    <td style="width:20%" align="right">
                                        <a href="https://twitter.com/Maishapay" style="text-decoration:none"
                                           target="_blank">
                                            <img alt="Twitter"
                                                 src="https://blockchain.info/Resources/twitter-email@2x.png"
                                                 style="height:35px;margin-right:4px;vertical-align:middle"
                                                 class="CToWUd">
                                        </a>
                                        <a href="https://www.facebook.com/Maishapay/" style="text-decoration:none"
                                           target="_blank"
                                           data-saferedirecturl="https://www.facebook.com/Maishapay/">
                                            <img alt="Facebook"
                                                 src="https://blockchain.info/Resources/facebook-email@2x.png"
                                                 style="height:35px;margin-left:4px;vertical-align:middle"
                                                 class="CToWUd">
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size:12px;line-height:1.5;padding-top:20px"
                                        align="center">Copyright © 2017 Maishapay Lubumbashi S.A. <span
                                                class="m_5281187580119612131mobile-footer-break">All rights reserved.</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="font-size:12px;padding-top:5px" align="center">
                                        Click here to <a
                                                href="https://Maishapay.com"
                                                style="color:#22af43;text-decoration:none" target="_blank">Unsubscribe</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>';
        return $template;
    }
}