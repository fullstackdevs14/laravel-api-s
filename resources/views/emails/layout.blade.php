<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Inscription et validation de l'email.</title>
    <style type="text/css">
        /* Take care of image borders and formatting */
        img {
            max-width: 600px;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: none;
        }

        table {
            border-collapse: collapse !important;
        }

        #outlook a {
            padding: 0;
        }

        table td {
            border-collapse: collapse;
        }

        .ExternalClass * {
            line-height: 115%;
        }

        /* General styling */
        td {
            font-family: Arial, sans-serif;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
            width: 100%;
            height: 100%;
            color: #6f6f6f;
            font-weight: 400;
            font-size: 18px;
        }

        h1 {
            margin: 10px 0;
        }

        a {
            color: #FFFFFF;
            text-decoration: none;
        }

        .force-full-width {
            width: 100% !important;
        }


    </style>

    <style type="text/css" media="screen">
        @media screen {
        @import url(http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,900);
            /* Thanks Outlook 2013! */
            * {
                font-family: 'Source Sans Pro', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
            }
        }
    </style>

    <style type="text/css" media="only screen and (max-width: 599px)">
        /* Mobile styles */
        @media only screen and (max-width: 599px) {

            table[class*="w320"] {
                width: 320px !important;
            }

            td[class*="w320"] {
                width: 280px !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }

            img[class*="w320"] {
                width: 250px !important;
                height: 67px !important;
            }

            td[class*="mobile-spacing"] {
                padding-top: 10px !important;
                padding-bottom: 10px !important;
            }

            *[class*="mobile-hide"] {
                display: none !important;
                width: 0 !important;
            }

            *[class*="mobile-br"] {
                font-size: 12px !important;
            }

            td[class*="mobile-center"] {
                text-align: center !important;
            }

            table[class*="columns"] {
                width: 100% !important;
            }

            td[class*="column-padding"] {
                padding: 0 50px !important;
            }

        }
    </style>
</head>
<body offset="0" class="body"
      style="padding:0; margin:0; display:block; background:#eeebeb; -webkit-text-size-adjust:none" bgcolor="#eeebeb">
<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">
    <tr>
        <td align="center" valign="top" style="background-color:#eeebeb" width="100%">

            <center>

                <table cellspacing="0" cellpadding="0" width="600" class="w320">
                    <tr>
                        <td align="center" valign="top">


                            <table style="margin:0 auto;" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td style="text-align: center;">
                                        <a href="{{ \Illuminate\Support\Facades\Config::get('constants.company_website') }}"><img
                                                    class="w320" width="311" height="83"
                                                    src="{{ \Illuminate\Support\Facades\Config::get('constants.base_url') }}img/Sipper-logo.svg"
                                                    alt="company logo"></a>
                                    </td>
                                </tr>
                            </table>

                            @yield('title')

                            @yield('body')

                            <table cellspacing="0" cellpadding="0" bgcolor="#363636" class="force-full-width">
                                <tr>
                                    <td style="background-color:#363636; text-align:center;">
                                        <br>
                                        <br>
                                        <a href="https://www.facebook.com/groups/1382341051775988">
                                            <img width="68" height="56"
                                                 src="https://www.filepicker.io/api/file/W6gXqm5BRL6qSvQRcI7u">
                                        </a>
                                        <a href="https://twitter.com/Sipper_app?lang=fr">
                                            <img width="61" height="56"
                                                 src="https://www.filepicker.io/api/file/eV9YfQkBTiaOu9PA9gxv">
                                        </a>
                                        <br>
                                        <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#f0f0f0; font-size: 14px; text-align:center; padding-bottom:4px;">
                                        © 2017 All Rights Reserved
                                    </td>
                                </tr>
                                <tr>
                                    <td style="color:#27aa90; font-size: 14px; text-align:center;">
                                        <a href="mailto:{{ \Illuminate\Support\Facades\Config::get('constants.mail_main') }}">Contact</a>
                                        <!--| <a href="#">Unsubscribe</a>-->
                                        <br/>
                                        <br/>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

            </center>

        </td>
    </tr>
</table>
</body>

</html>