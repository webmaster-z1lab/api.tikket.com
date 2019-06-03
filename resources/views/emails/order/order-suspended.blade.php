@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message',[
        'image' => $image,
        'button' => $url,
    ])
        <h2>Oi {{ explode(' ', $order->customer->name)[0] }}!</h2>
        <h2>A sua assinatura foi suspensa</h2>

        <p>
            A operadora do seu cartão de crédito não aprovou o pagamento da sua assinatura e por isso ela foi temporiamente suspensa.
        </p>

        @component('emails.components.contact-alert')
            Fique tranquilo! Para atualizar os dados de pagamento e reativar a sua assinatura é só clicar no botão ai embaixo.
        @endcomponent

        <h3>Detalhes da assinatura</h3>
        <hr>
        <ul>
            <li style="text-transform: uppercase">Plano: {{ $order->plan->name }}</li>
            <li>Empresa: {{ $order->place->name }}</li>
            <li>Data do pedido: {{ $order->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    @endcomponent
@endsection
