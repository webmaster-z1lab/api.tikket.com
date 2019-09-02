<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;"/>
    <title>E-mail de notificação do Tikket.com.br | Eventos, inscrições e ingressos online</title>

    <style type="text/css">

        body {width : 100%; background-color : #0d0b0c; margin : 0; padding : 0; -webkit-font-smoothing : antialiased;mso-margin-top-alt : 0px; mso-margin-bottom-alt : 0px; mso-padding-alt : 0px 0px 0px 0px;}

        p, h1, h2, h3, h4 {margin-top : 0;margin-bottom : 0;padding-top : 0;padding-bottom : 0;}

        span.preheader {display : none; font-size : 1px;}

        html {width : 100%;}

        table {font-size : 12px;border : 0;}

        .menu-space {padding-right : 25px;}

        a, a:hover { text-decoration : none; color : #FFF;}


        @media only screen and (max-width : 640px) {
            body {width : auto !important;}

            table [class=main] {width : 440px !important;}

            table [class=two-left] {width : 420px !important; margin : 0px auto;}

            table [class=full] {width : 100% !important; margin : 0px auto;}

            table [class=two-left-inner] {width : 400px !important; margin : 0px auto;}

            table [class=menu-icon] { display : none;}

        }

        @media only screen and (max-width : 479px) {
            body {width : auto !important;}

            table [class=main] {width : 310px !important;}

            table [class=two-left] {width : 300px !important; margin : 0px auto;}

            table [class=full] {width : 100% !important; margin : 0px auto;}

            table [class=two-left-inner] {width : 280px !important; margin : 0px auto;}

            table [class=menu-icon] { display : none;}


        }


    </style>

</head>

<body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!--Main Table Start-->

<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#0d0b0c">
    <tr>
        <td align="center" valign="top">
            <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
                <tr>
                    <td height="100" align="center" valign="top" style="font-size:100px; line-height:100px;">&nbsp;</td>
                </tr>
                <tr>
                    <td height="330" align="center" valign="top" style="background:#FFFFFF; -moz-border-radius: 4px 4px 0px 0px; border-radius: 4px 4px 0px 0px;">
                        <table width="510" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left">
                            @include('emails.components.header')

                            <tr>
                                <td align="left" valign="top">
                                    <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0" class="two-left">

                                        <tr>
                                            <td align="center" valign="top">
                                                <table width="64" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td align="center" valign="top">
                                                            <img src="{{ $image['source'] }}" width="400px" alt="{{ $image['text'] }}"/>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                        @yield('content')

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @include('emails.components.address')
                @include('emails.components.footer')

            </table>
        </td>
    </tr>
</table>

<!--Main Table End-->

</body>
</html>
