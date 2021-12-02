<?php

namespace App\Http\Controllers;

use App\Repositories\OrderRepository;
use App\Repositories\PointRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var OrderRepository
     */
    private $orderRepository;
    /**
     * @var PointRepository
     */
    private $pointRepository;
    private $roles = [
        'client',
        'admin',
        'manager'
    ];

    public function __construct()
    {
        $this->userRepository = app(UserRepository::class);
        $this->orderRepository = app(OrderRepository::class);
        $this->pointRepository = app(PointRepository::class);
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
            'orders' => $this->orderRepository->getOrders(),
            'managersInfo' => $this->orderRepository->getManagersInfo(),
            'pointsInfo' => $this->orderRepository->getPointsInfo(),
            'scootersInfo' => $this->orderRepository->getScootersInfo(),
            'clientsInfo' => $this->orderRepository->getClientsInfo()
        ]);
    }

    private function managerDashboard()
    {
        return view('app.manager-dashboard', [
            'orders' => $this->orderRepository->getOrders()
        ]);
    }

    private function clientDashboard()
    {
        $currentId = Auth::id();
        return view('app.client-dashboard', [
            'orders' => $this->orderRepository->getOrdersByUser($currentId),
            'activeOrder' => $this->orderRepository->getActiveOrder($currentId),
            'points' => $this->pointRepository->getPoints()
        ]);
    }
}
