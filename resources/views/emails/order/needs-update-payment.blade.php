@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', [
        'image' => $image,
        'button' => $url,
    ])
        <h2>Oi {{ explode(' ', $order->costumer->name)[0] }}!</h2>
        <h2>A sua assinatura precisa de atenção</h2>

        <p>
            O seu cartão de crédito expirou e precisa ser atualizado. Enquanto isso a sua assinatura está temporiamente suspensa.
        </p>

        @component('emails.components.contact-alert')
            Fique tranquilo! Para atualizar os dados de pagamento e reativar a sua assinatura, é só clicar ai embaixo!
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
