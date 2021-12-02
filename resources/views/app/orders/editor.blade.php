@section('content')

    <form method="POST" class="p_form" action="{{ route('orders.update', ['order' => $order->id]) }}">
        @method('PUT')
        @csrf
        @error('price')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Цена:
            <input type="text" name="price" class="input_default" value="{{ old('price', $order->price) }}" />
        </label>
        <label>
            Статус заказа:
            <select name="status">
                @php
                    $selected = old('status', $order->status);
                @endphp
                @foreach($statuses as $index => $status)
                    <option value="{{ $index }}"@if($selected == $index) selected @endif>{{ $status }}</option>
                @endforeach
            </select>
        </label>
        <label>
            Залоговые данные:
            <textarea name="collateral">{{ old('collateral', $order->collateral) }}</textarea>
        </label>
        <input type="submit" class="button green" value="Сохранить">
    </form>

@endsection

@extends('layouts.layout')
