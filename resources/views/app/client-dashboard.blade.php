@section('content')

    <p class="heading left">История заказов:</p>
    <table class="big_table">
        <tr class="head">
            <td>#</td>
            <td>Точка выдачи</td>
            <td>Самокат</td>
            <td>Менеджер</td>
            <td>Стоимость поездки</td>
            <td>Статус</td>
            <td>Дата</td>
        </tr>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->point->street }}</td>
            <td>{{ $order->scooter->num }}</td>
            <td>{{ $order->manager->name ?? "-" }}</td>
            <td>{{ $order->price ?? "не задана" }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->date }}</td>
        </tr>
    @endforeach
    </table>

    @if($activeOrder != null)

        <p class="heading left">Активный заказ:</p>
        <table class="big_table">
            <tr class="head">
                <td>#</td>
                <td>Точка выдачи</td>
                <td>Самокат</td>
                <td>Менеджер</td>
                <td>Стоимость поездки</td>
                <td>Статус</td>
                <td>Дата</td>
                <td>Действия</td>
            </tr>
            <tr>
                <td>{{ $activeOrder->id }}</td>
                <td>{{ $activeOrder->point->street }}</td>
                <td>{{ $activeOrder->scooter->num }}</td>
                <td>{{ $activeOrder->name != null ? $activeOrder->name : "-" }}</td>
                <td>{{ $activeOrder->price != null ? $activeOrder->price : "не задана" }}</td>
                <td class="status_st">{{ $activeOrder->status }}</td>
                <td class="timer_cd">{{ $activeOrder->date }}</td>
                <td class="action_section">
                    <form method="POST" action="{{ route('orders.destroy', ['order' => $activeOrder->id]) }}">
                        @method('DELETE')
                        @csrf
                        <input type="submit" class="button small red" value="Отменить" />
                    </form>
                </td>
            </tr>
        </table>
        <p class="booked_text"><strong>Самокат забронирован на 15 минут, бронь снимется через: <span class="timer"><span class="timer_mins"></span>:<span class="timer_secs"></span></span></strong></p>

    @else

        <p class="heading left">Создать новый заказ:</p>
        <form method="POST" action="{{ route('orders.store') }}">
            @csrf
            <label>
                Точка выдачи:
                <select name="point" class="point_select">
                    <option value="0">Выбрать</option>
                    @foreach($points as $point)
                        <option value="{{ $point->id }}">{{ $point->street }}</option>
                    @endforeach
                </select>
            </label>
            <label>
                Самокат:
                <select name="scooter" class="scooter_select"></select>
            </label>
            <input type="submit" class="button green" value="Создать" />
        </form>

    @endif

@endsection

@extends('layouts.layout')
