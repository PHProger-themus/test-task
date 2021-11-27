@section('content')

    @error('error')
    <div class="input_error">{{ $message }}</div>
    @enderror

    <a href="{{ route('points.create') }}" class="button green margined">Создать</a>

    <table class="big_table">
        <tr class="head">
            <td>#</td>
            <td>Улица</td>
            <td>Действия</td>
        </tr>
        @foreach($points as $point)
            <tr>
                <td>{{ $point->id }}</td>
                <td>{{ $point->street }}</td>
                <td class="action_section">
                    <a href="{{ route('points.edit', ['point' => $point->id]) }}" class="button small blue">Изменить</a>
                    <form method="POST" action="{{ route('points.destroy', ['point' => $point->id]) }}">
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
