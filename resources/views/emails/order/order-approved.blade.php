@extends('emails.layout')

@section('description', $description)

@section('content')
    @component('emails.components.message', [
        'image' => $image,
        'button' => $url
    ])
        <h2>Oi {{ explode(' ', $order->costumer->name)[0] }}!</h2>
        <h2>Obrigado por assinar o quantofica.com</h2>

        <p>
            A sua assinatura no quantofica.com está ativa e você já pode aproveitar seus 30 dias gratuitos para conhecer todas as ferramentas do nosso site. É só clicar no botão ai embaixo para abrir o site e gerir os dados da sua empresa.
        </p>

        <h3>Detalhes do pedido</h3>
        <hr>
        <ul>
            <li style="text-transform: uppercase">Plano: {{ $order->plan->name }}</li>
            <li>Empresa: {{ $order->place->name }}</li>
            <li>Data do pedido: {{ $order->created_at->format('d/m/Y H:i') }}</li>
        </ul>
    @endcomponent
@endsection
