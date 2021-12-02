@section('content')

    <div class="top_buttons">
        <a href="{{ route('users.index') }}" class="button margined">Пользователи</a>
        <a href="{{ route('scooters.index') }}" class="button margined">Самокаты</a>
        <a href="{{ route('points.index') }}" class="button margined">Точки выдачи</a>
    </div>

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
        </tr>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->point->street }}</td>
            <td>{{ $order->scooter->num }}</td>
            <td>{{ $order->manager->name ?? "-" }}</td>
            <td>{{ $order->price }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->collateral }}</td>
            <td>{{ $order->date }}</td>
        </tr>
    @endforeach
    </table>

    <div class="tables_flex">

        <table>
            <tr class="head">
                <td>Менеджер</td>
                <td>Кол-во обработанных заказов</td>
            </tr>
            @foreach($managersInfo as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->count }}</td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr class="head">
                <td>Точка выдачи</td>
                <td>Кол-во выданных самокатов</td>
            </tr>
            @foreach($pointsInfo as $row)
                <tr>
                    <td>{{ $row->street }}</td>
                    <td>{{ $row->count }}</td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr class="head">
                <td>Самокат</td>
                <td>Кол-во поездок</td>
            </tr>
            @foreach($scootersInfo as $row)
                <tr>
                    <td>{{ $row->num }}</td>
                    <td>{{ $row->count }}</td>
                </tr>
            @endforeach
        </table>

        <table>
            <tr class="head">
                <td>Клиент</td>
                <td>Кол-во поездок</td>
            </tr>
            @foreach($clientsInfo as $row)
                <tr>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->count }}</td>
                </tr>
            @endforeach
        </table>

    </div>

@endsection

@extends('layouts.layout')
