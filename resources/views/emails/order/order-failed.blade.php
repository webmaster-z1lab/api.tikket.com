@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', [
        'image' => $image,
        'button' => $url,
    ])
        <h2>Oi {{ explode(' ', $order->customer->name)[0] }}!</h2>
        <h2>Não conseguimos finalizar o seu pedido</h2>

        <p>
            Infelizmente não conseguimos concluir o seu pedido. A operadora do seu cartão de crédito não aprovou o pagamento da sua assinatura.
        </p>

        @component('emails.components.contact-alert')
            Fique tranquilo! Para atualizar os dados de pagamento e refazer o pedido é só clicar no botão ai embaixo.
        @endcomponent

        <h3>Detalhes do pedido</h3>
        <hr>
        <ul>
            <li style="text-transform: uppercase">Plano: {{ $order->plan->name }}</li>
            <li>Empresa: {{ $order->place->name }}</li>
            <li>Data do pedido: {{ $order->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    @endcomponent
@endsection
