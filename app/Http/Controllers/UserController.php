<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    private const CREATE = 0;
    private const EDIT = 1;
    private $roles = [
        'Клиент',
        'Администратор',
        'Менеджер'
    ];

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
    }

    public function signUp()
    {
        return view('sign-up');
    }

    public function authenticate(AuthRequest $request): \Illuminate\Http\RedirectResponse
    {
        $inputs = $request->validated();
        return $this->loginUser($inputs['email'], $inputs['password']);
    }

    private function loginUser($email, $password): \Illuminate\Http\RedirectResponse
    {
        $user = $this->userRepository->getUserByFormData($email, $password);
        if ($user) {
            Auth::login($user);
            return redirect()->route('dashboard');
        } else {
            return back()->withErrors(['error' => 'Неверный пароль'])->withInput();
        }
    }

    public function logoutUser()
    {
        $this->userRepository->logout();
        return redirect()->route('index');
    }

    public function index()
    {
        return view('app.users.index', [
            'users' => $this->userRepository->getUsers(),
            'roles' => $this->roles
        ]);
    }

    public function create()
    {
        return view('app.users.editor', [
            'mode' => UserController::CREATE,
            'roles' => $this->roles
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $inputs = $request->validated();
        $this->userRepository->storeUser($inputs);

        if (defined("IS_ADMIN")) {
            return redirect()->route('users.index');
        } else {
            return $this->loginUser($request->input('email'), $request->input('password'));
        }
    }

    public function edit(User $user)
    {
        return view('app.users.editor', [
            'mode' => UserController::EDIT,
            'user' => $user,
            'roles' => $this->roles
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $inputs = $request->validated();
        $this->userRepository->updateUser($user, $inputs);
        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        try {
            $this->userRepository->destroyUser($user);
            return redirect()->route('users.index');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Ошибка удаления записи']);
        }
    }
}
