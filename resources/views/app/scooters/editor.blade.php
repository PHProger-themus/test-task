@section('content')

    @if ($mode)
        <form method="POST" class="p_form" action="{{ route('scooters.update', ['scooter' => $scooter->id]) }}">
        @method('PUT')
    @else
        <form method="POST" class="p_form" action="{{ route('scooters.store') }}">
    @endif

        @csrf
        @error('num')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Номер:
            <input type="text" name="num" class="input_default" value="@if($mode){{ old('num', $scooter->num) }}@else{{ old('num') }}@endif" />
        </label>
        <label>
            Точка выдачи:
            <select name="point">
                @php
                    if ($mode) {
                        $selected = old('point', $scooter->point_id);
                    } else {
                        $selected = old('point');
                    }
                @endphp
                @foreach($points as $point)
                    <option value="{{ $point->id }}"@if($selected == $point->id) selected @endif>{{ $point->street }}</option>
                @endforeach
            </select>
        </label>
        <input type="submit" class="button green" value="Сохранить">
    </form>

@endsection

@extends('layouts.layout')
