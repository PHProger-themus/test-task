@section('content')

    <div class="auth_form">
        <p class="heading">Регистрация</p>
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="inputs">
                <label for="nickname">Имя:</label>
                @error('name')
                <p class="error">{{ $message }}</p>
                @enderror
                <input type="text" name="name" class="auth_input" value="{{ old('name') }}" />
            </div>
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
            <input type="submit" class="button" value="Зарегистрироваться" />
        </form>
    </div>

@endsection

@extends('layouts.layout')

