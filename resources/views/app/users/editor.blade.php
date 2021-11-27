@section('content')

    @if ($mode)
        <form method="POST" class="p_form" action="{{ route('users.update', ['user' => $user->id]) }}">
        @method('PUT')
    @else
        <form method="POST" class="p_form" action="{{ route('users.store') }}">
    @endif

        @csrf
        @error('name')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Имя:
            <input type="text" name="name" class="input_default" value="@if($mode){{ old('name', $user->name) }}@else{{ old('name') }}@endif" />
        </label>
        @error('email')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Email:
            <input type="text" name="email" class="input_default" value="@if($mode){{ old('email', $user->email) }}@else{{ old('email') }}@endif" />
        </label>
        @error('password')
        <div class="input_error">{{ $message }}</div>
        @enderror
        <label>
            Пароль:
            <input type="text" name="password" class="input_default" />
        </label>
        <label>
            Роль:
            <select name="role">
                @php
                    if ($mode) {
                        $selected = old('role', $user->role);
                    } else {
                        $selected = old('role');
                    }
                @endphp
                @foreach($roles as $index => $role)
                    <option value="{{ $index }}"@if($selected == $index) selected @endif>{{ $role }}</option>
                @endforeach
            </select>
        </label>
        <input type="submit" class="button green" value="Сохранить">
    </form>

@endsection

@extends('layouts.layout')
