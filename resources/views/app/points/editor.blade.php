@section('content')

    @if ($mode)
        <form method="POST" class="p_form" action="{{ route('points.update', ['point' => $point->id]) }}">
        @method('PUT')
    @else
        <form method="POST" class="p_form" action="{{ route('points.store') }}">
    @endif

        @csrf
        @error('street')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Улица:
            <input type="text" name="street" class="input_default" value="@if($mode){{ old('street', $point->street) }}@else{{ old('street') }}@endif" />
        </label>
        <input type="submit" class="button green" value="Сохранить">
    </form>

@endsection

@extends('layouts.layout')
