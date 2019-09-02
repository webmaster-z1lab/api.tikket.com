@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', ['button' => $button])
        <tr>
            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#26436E; font-weight:normal; line-height:32px;"> </td>
        </tr>
        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:34px; color:#6000A7; font-weight:bold; text-transform:uppercase;">
                Recuperar senha de acesso
            </td>
        </tr>
        <tr>
            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:0px 25px;">
                Olá <b>{{ $user['name'] }}</b>, vamos recuperar o seu acesso ao tikket.com.br.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:0px 25px;">
                Você está recebendo esse email porque solicitou uma recuperação de senha em nosso site.
                Para continuar com o processo e cadastrar uma nova senha de acesso basta clicar
                no botão abaixo.
            </td>
        </tr>
    @endcomponent
@endsection
