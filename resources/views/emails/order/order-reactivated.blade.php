@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', [
        'image' => $image,
        'button' => $url,
    ])
        <h2>Oi {{ explode(' ', $order->customer->name)[0] }}!</h2>
        <h2>A sua assinatura foi reativada</h2>

        <p>
            O pagamento da sua assinatura foi confirmado e ela foi reativada. Você agora pode voltar a acessar os recursos avançados da sua empresa.
        </p>

        <h3>Detalhes da assinatura</h3>
        <hr>
        <ul>
            <li style="text-transform: uppercase">Plano: {{ $order->plan->name }}</li>
            <li>Empresa: {{ $order->place->name }}</li>
            <li>Data do pedido: {{ $order->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    @endcomponent
@endsection
