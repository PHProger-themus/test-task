@section('content')

    @error('error')
    <div class="input_error">{{ $message }}</div>
    @enderror

    <a href="{{ route('scooters.create') }}" class="button green margined">Создать</a>

    <table class="big_table">
        <tr class="head">
            <td>#</td>
            <td>Номер</td>
            <td>Точка выдачи</td>
            <td>Забронирован пользователем</td>
            <td>Дата брони</td>
            <td>Действия</td>
        </tr>
        @foreach($scooters as $scooter)
            <tr>
                <td>{{ $scooter->id }}</td>
                <td>{{ $scooter->num }}</td>
                <td>{{ $scooter->street }}</td>
                <td>{{ $scooter->booked_by }}</td>
                <td>{{ $scooter->booked_at }}</td>
                <td class="action_section">
                    <a href="{{ route('scooters.edit', ['scooter' => $scooter->id]) }}" class="button small blue">Изменить</a>
                    <form method="POST" action="{{ route('scooters.destroy', ['scooter' => $scooter->id]) }}">
                        @method('DELETE')
                        @csrf
                        <input type="submit" class="button small red" value="Удалить" />
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

@endsection

@extends('layouts.layout')
