@extends('emails.layout')

@section('description', $description)

@section('content')

    @component('emails.components.message', ['button' => $button])
        <tr>
            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#6000A7; font-weight:normal; line-height:32px;"> </td>
        </tr>
        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:34px; color:#6000A7; font-weight:bold; text-transform:uppercase;">
                Bem-vindo ao Tikket
            </td>
        </tr>
        <tr>
            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Olá <b>{{ $user->name }}</b>, você acaba de se cadastrar no site <a style="color: #000000" href="http://tikket.com.br">tikket.com.br</a> e sua conta está pronta
                para começar a comprar e vender ingressos online.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px;
            padding:0px 25px;">
                Para poder utilizar todas as funcionalidades do nosso site basta clicar no botão abaixo e confirmar o seu email.
            </td>
        </tr>
    @endcomponent
@endsection
