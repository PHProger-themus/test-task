<?php

namespace App\Http\Controllers;

use App\Repositories\AppRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $userRepository, $appRepository;
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
        $this->appRepository = app(AppRepository::class);
    }

    public function signUp()
    {
        return view('sign-up');
    }

    public function authenticate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:4'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return $this->loginUser($request->input('email'), $request->input('password'));
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

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('index');
    }

    public function index()
    {
        if (Auth::user()->role == 1) {
            return view('app.users.index', [
                'users' => $this->appRepository->getUsers(),
                'roles' => $this->roles
            ]);
        } else {
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        if (Auth::user()->role == 1) {
            return view('app.users.editor', [
                'mode' => self::CREATE,
                'roles' => $this->roles
            ]);
        } else {
            return back();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role == 1) {
            $userIsCreatedByAdmin = Auth::check() && Auth::user()->role == 1;
            $rules = [
                'name' => 'required|min:2',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:4'
            ];
            if ($userIsCreatedByAdmin) {
                $rules['role'] = 'required|numeric';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            DB::table('users')->insert([
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'password' => Hash::make($request->post('password')),
                'role' => $request->post('role') ?? 0, // If role is in request (admin is creating user), set this value. Otherwise, user is client by default (registration)
            ]);

            if ($userIsCreatedByAdmin) {
                return redirect()->route('users.index');
            } else {
                return $this->loginUser($request->input('email'), $request->input('password'));
            }
        } else {
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        if (Auth::user()->role == 1) {
            return view('app.users.editor', [
                'mode' => self::EDIT,
                'user' => $this->appRepository->getUser($id),
                'roles' => $this->roles
            ]);
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role == 1) {
            $rules = [
                'name' => 'required|min:2',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:4',
                'role' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $password = trim($request->post('password'));
            $updateArray = [
                'name' => $request->post('name'),
                'email' => $request->post('email'),
                'role' => $request->post('role'),
            ];
            if (!empty($password)) {
                $updateArray['password'] = Hash::make($request->post('password')); // If password is not empty, change it. Otherwise, keep the old one.
            }
            DB::table('users')->where('id', '=', $id)->update($updateArray);
            return redirect()->route('users.index');
        } else {
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        if ($id != Auth::id() || Auth::user()->role != 1) {
            try {
                DB::table('users')->where('id', '=', $id)
                    ->delete();
                return redirect()->route('users.index');
            } catch (QueryException $e) {
                return back()->withErrors(['error' => 'Ошибка удаления записи']);
            }
        } else {
            return back();
        }
    }
}
