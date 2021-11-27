@section('content')

    <div class="auth_form">
        <p class="heading">Войти в систему</p>
        <form method="POST" action="{{ route('auth') }}">
            @csrf
            <div class="inputs">
                <label for="nickname">Email:</label>
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
                <input type="text" name="email" class="auth_input" value="{{ old('email') }}" />
            </div>
            <div class="inputs">
                <label for="email">Пароль:</label>
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
                <input type="password" name="password" class="auth_input" />
            </div>
            <div class="auth_buttons">
                <a href="{{ route('reg') }}" class="button margined">Быстрая регистрация</a>
                <input type="submit" class="button margined green" value="Войти" />
            </div>
        </form>
    </div>

@endsection

@extends('layouts.layout')

