@extends('emails.layout')

@section('description', $params['text'])

@section('content')
    @component('emails.components.message', ['button' => $button])
        <tr>
            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#6000A7; font-weight:normal; line-height:32px;"></td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:34px; color:#6000A7; font-weight:bold; text-transform:uppercase;">
                Pedido cancelado
            </td>
        </tr>

        <tr>
            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Olá <b>{{ $order->customer->name }}</b>, o seu pedido de ingressos para o evento <b>{{ $order->event->name }}</b> foi cancelado.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Para detalhes sobre a transação basta acessar o nosso site pelo link abaixo.
            </td>
        </tr>
    @endcomponent
@endsection
