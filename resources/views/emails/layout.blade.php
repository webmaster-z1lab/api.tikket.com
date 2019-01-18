<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>@yield('title')</title>

    <style>
        html,
        body {
            margin  : 0 auto !important;
            padding : 0 !important;
            height  : 100% !important;
            width   : 100% !important;
        }

        * {
            -ms-text-size-adjust     : 100%;
            -webkit-text-size-adjust : 100%;
        }

        div[style*="margin: 16px 0"] {
            margin : 0 !important;
        }

        table,
        td {
            mso-table-lspace : 0 !important;
            mso-table-rspace : 0 !important;
        }

        table {
            border-spacing  : 0 !important;
            border-collapse : collapse !important;
            table-layout    : fixed !important;
            margin          : 0 auto !important;
        }

        table table table {
            table-layout : auto;
        }

        img {
            -ms-interpolation-mode : bicubic;
        }

        a {
            text-decoration : none;
        }

        *[x-apple-data-detectors], /* iOS */
        .unstyle-auto-detected-links *,
        .aBn {
            border-bottom   : 0 !important;
            cursor          : default !important;
            color           : inherit !important;
            text-decoration : none !important;
            font-size       : inherit !important;
            font-family     : inherit !important;
            font-weight     : inherit !important;
            line-height     : inherit !important;
        }

        .a6S {
            display : none !important;
            opacity : 0.01 !important;
        }

        .im {
            color : inherit !important;
        }

        img.g-img + div {
            display : none !important;
        }

        @media only screen and (min-device-width : 320px) and (max-device-width : 374px) {
            u ~ div .email-container {
                min-width : 320px !important;
            }
        }

        @media only screen and (min-device-width : 375px) and (max-device-width : 413px) {
            u ~ div .email-container {
                min-width : 375px !important;
            }
        }

        @media only screen and (min-device-width : 414px) {
            u ~ div .email-container {
                min-width : 414px !important;
            }
        }
    </style>

    <!--[if mso]>
    <style type="text/css">
        ul,
        ol {
            margin : 0 !important;
        }

        li {
            margin-left : 30px !important;
        }

        li.list-item-first {
            margin-top : 0 !important;
        }

        li.list-item-last {
            margin-bottom : 10px !important;
        }
    </style>
    <![endif]-->

    <!-- Progressive Enhancements : BEGIN -->
    <style>
        body {
            font-family : "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
        }

        a {
            color : #377DFF;
        }

        h1 {
            font-family : Verdana, sans-serif;
            margin      : 0 0 10px 0;
            font-size   : 18px;
            color       : #333333;
            font-weight : normal;
        }

        ul {
            font-family     : Verdana, sans-serif;
            padding         : 0;
            margin          : 0 0 10px 0;
            list-style-type : disc;
        }

        li {
            font-family : Verdana, sans-serif;
            margin      : 0 0 10px 30px;
        }

        h2 {
            font-family : Verdana, sans-serif;
            margin      : 0 0 10px 0;
            font-size   : 18px;
            color       : #333333;
            font-weight : normal;
        }

        h3 {
            font-family : Verdana, sans-serif;
            margin      : 0 0 10px 0;
            font-size   : 16px;
            color       : #333333;
            font-weight : normal;
        }

        .button-td, .button-a {
            transition : all 100ms ease-in;
        }

        .button-td-primary:hover,
        .button-a-primary:hover {
            background   : #377DFF !important;
            border-color : #377DFF !important;
        }

        @media screen and (max-width : 600px) {
            .email-container p {
                font-size : 17px !important;
            }
        }
    </style>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->

</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #F8F9FA;">
<center style="width: 100%; background-color: #F8F9FA;">
    <!--[if mso | IE]>
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #F8F9FA;">
        <tr>
            <td>
    <![endif]-->
    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        @yield('description', 'E-mail enviando pelo quantofica.com, o seu portal busca de negócios locais. Um projeto Z1lab.')
    </div>

    <div style="display: none; font-size: 1px; line-height: 1px; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; font-family: sans-serif;">
        &zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;
    </div>

    <div style="max-width: 600px; margin: 0 auto;" class="email-container">

        @include('emails.components.header')

        @yield('content')

        @include('emails.components.divider')

        @include('emails.components.no-reply')

        @include('emails.components.divider')
    </div>

    @include('emails.components.footer')

    <!--[if mso | IE]>
    </td>
    </tr>
    </table>
    <![endif]-->
</center>
</body>
</html>
