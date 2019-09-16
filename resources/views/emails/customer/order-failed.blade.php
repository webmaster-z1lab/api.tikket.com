@extends('emails.layout')

@section('description', $params['text'])

@section('content')
    @component('emails.components.message', ['button' => $button])
        <tr>
            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#6000A7; font-weight:normal; line-height:32px;"></td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:34px; color:#6000A7; font-weight:bold; text-transform:uppercase;">
                Falha no pagamento do seu pedido
            </td>
        </tr>

        <tr>
            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Olá <b>{{ $order->customer->name }}</b>, não conseguimos concluir o pagamento do seu pedido de ingressos para o evento <b>{{ $order->event->name }}</b>.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                <b>O pagamento foi negado pela operadora do seu cartão de crédito.</b>
            </td>
        </tr>

        <tr>
            <td align="center" valign="top">
                <table
                    style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px; margin: 15px 0; border: 1px solid black;">
                    <tr>
                        <td>Pedido REF:</td>
                        <td><b style="text-transform: uppercase;">{{ $order->code }}</b></td>
                    </tr>

                    <tr>
                        <td>Nome:</td>
                        <td><b style="text-transform: uppercase;">{{ $order->card->holder->name }}</b></td>
                    </tr>

                    <tr>
                        <td>Cartão de Crédito:</td>
                        <td><b style="text-transform: uppercase;">{{ $order->card->brand }}</b></td>
                    </tr>

                    <tr>
                        <td>Número:</td>
                        <td><b>**** **** **** {{ $order->card->number }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Mas não se preocupe, você pode realizar uma nova compra pelo nosso site acessando diretamente a página do evento a partir do link abaixo.
            </td>
        </tr>
    @endcomponent
@endsection
