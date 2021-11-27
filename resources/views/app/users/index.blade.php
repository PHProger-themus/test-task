@section('content')

    @error('error')
    <div class="input_error">{{ $message }}</div>
    @enderror

    <a href="{{ route('users.create') }}" class="button green margined">Создать</a>

    <table class="big_table">
        <tr class="head">
            <td>#</td>
            <td>Имя</td>
            <td>Email</td>
            <td>Роль</td>
            <td>Создан</td>
            <td>Действия</td>
        </tr>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $roles[$user->role] }}</td>
                <td>{{ $user->created_at }}</td>
                <td class="action_section">
                    <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="button small blue">Изменить</a>
                    @if($user->id != \Illuminate\Support\Facades\Auth::id())
                        <form method="POST" action="{{ route('users.destroy', ['user' => $user->id]) }}">
                            @method('DELETE')
                            @csrf
                            <input type="submit" class="button small red" value="Удалить" />
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

@endsection

@extends('layouts.layout')
