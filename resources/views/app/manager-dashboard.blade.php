@section('content')

    <table class="big_table">
        <tr class="head">
            <td>#</td>
            <td>Имя клиента</td>
            <td>Точка выдачи</td>
            <td>Самокат</td>
            <td>Менеджер</td>
            <td>Стоимость поездки</td>
            <td>Статус</td>
            <td>Залоговые данные</td>
            <td>Дата</td>
            <td>Действия</td>
        </tr>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->name }}</td>
            <td>{{ $order->street }}</td>
            <td>{{ $order->num }}</td>
            <td>{{ $order->manager }}</td>
            <td>{{ $order->price }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->collateral }}</td>
            <td>{{ $order->date }}</td>
            <td>
                <a href="{{ route('orders.edit', ['order' => $order->id]) }}" class="button small blue">Изменить данные</a>
            </td>
        </tr>
    @endforeach
    </table>

@endsection

@extends('layouts.layout')
