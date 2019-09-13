@extends('emails.layout')

@section('description', $params['text'])

@section('content')
    @component('emails.components.message', ['button' => $button])
        <tr>
            <td align="center" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#6000A7; font-weight:normal; line-height:32px;"></td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:34px; color:#6000A7; font-weight:bold; text-transform:uppercase;">
                Obrigado por comprar no Tikket
            </td>
        </tr>

        <tr>
            <td height="10" align="center" valign="top" style="font-size:10px; line-height:10px;">&nbsp;</td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Olá <b>{{ $order->customer->name }}</b>, acabamos de receber o seu pedido de ingressos para o evento <b>{{ $order->event->name }}</b>.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top"
                style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px; padding:5px 25px;">
                Para concluir o processo de compra basta pagar o boleto. Observe que as compras realizadas por boleto recebem um acréscimo de de R$ 1,00 (um real). Esse adicional
                corresponde a taxa de gestão de risco do meio de pagamento cobrada pelo PagSeguro que é atualmente nosso provedor de pagamentos. Essa taxa não é reembolsável.
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
                        <td>Vencimento do boleto:</td>
                        <td><b style="text-transform: uppercase;">{{ $order->boleto->due_at->format('d/m/Y') }}</b></td>
                    </tr>

                    <tr>
                        <td>Linha digitável:</td>
                        <td></td>
                    </tr>

                    <tr>
                        <td><b style="text-transform: uppercase;">{{ $order->boleto->barcode }}</b></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px;
            padding:0px 25px;">
                Para visualizar e imprimir o seu boleto basta acessar o link abaixo.
            </td>
        </tr>

        <tr>
            <td align="center" valign="top" style="font-family:'Open Sans', sans-serif, Verdana; font-size:15px; color:#000000; font-weight:normal; line-height:24px;
            padding:0px 25px;">
                Lembramos que seus ingressos só ficarão disponíveis após o pagamento desse boleto bancário. O período de confirmação é de até 3 dias úteis.
            </td>
        </tr>
    @endcomponent
@endsection
