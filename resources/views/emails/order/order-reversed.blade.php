@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', [
        'image' => $image,
        'button' => $url,
    ])
        <h2>Oi {{ explode(' ', $order->costumer->name)[0] }}!</h2>
        <h2>A sua assinatura foi cancelada</h2>

        <p>
            Infelizmente a sua assinatura foi cancelada e sua empresa agora possui somente as ferramentas gratuitas do nosso site.
        </p>

        @component('emails.components.contact-alert')
            Fique tranquilo! Para refazer a sua assinatura é só clicar no botão ai embaixo.
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