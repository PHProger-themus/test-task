<?php

namespace App\Http\Controllers;

use App\Repositories\AppRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{

    private $userRepository;
    private $appRepository;
    private $roles = [
        'client',
        'admin',
        'manager'
    ];

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
        $this->appRepository = app(AppRepository::class);
    }

    public function dashboard(Request $request)
    {
        $user = $this->userRepository->initUser();
        $method = $this->roles[$user->role] . 'Dashboard';
        return $this->$method();
    }

    private function adminDashboard()
    {
        return view('app.admin-dashboard', [
            'orders' => $this->appRepository->getOrders(),
            'managersInfo' => $this->appRepository->getManagersInfo(),
            'pointsInfo' => $this->appRepository->getPointsInfo(),
            'scootersInfo' => $this->appRepository->getScootersInfo(),
            'clientsInfo' => $this->appRepository->getClientsInfo()
        ]);
    }

    private function managerDashboard()
    {
        return view('app.manager-dashboard', [
            'orders' => $this->appRepository->getOrders()
        ]);
    }

    private function clientDashboard()
    {
        $currentId = Auth::id();
        return view('app.client-dashboard', [
            'orders' => $this->appRepository->getOrdersByUser($currentId),
            'points' => $this->appRepository->getPoints(),
            'activeOrder' => $this->appRepository->getActiveOrder($currentId)
        ]);
    }
}
