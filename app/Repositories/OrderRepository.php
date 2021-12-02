<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderRepository
{

    private $statuses = [
        'В ожидании',
        'Выдан',
        'Завершен',
        'Отменен',
    ];
    public const PENDING = 0;
    public const ISSUED = 1;
    public const COMPLETED = 2;
    public const CANCELLED = 3;

    public function getOrders(): \Illuminate\Support\Collection
    {
        $this->cancelOldOrders();

        $orders = Order::orderBy('orders.id')
            ->get();
        foreach ($orders as &$order) {
            $order->status = $this->statuses[$order->status];
        }

        return $orders;
    }

    private function cancelOldOrders()
    {
        $orders = Order::whereRaw("date < NOW() - INTERVAL '15 minutes'")
            ->where('status', OrderRepository::PENDING)
            ->update(['status' => OrderRepository::CANCELLED]);
    }

    public function getStatuses(): array
    {
        return $this->statuses;
    }

    public function getManagersInfo()
    {
        return $this->getDataUsing('users', 'manager_id', 'name');
    }

    public function getPointsInfo()
    {
        return $this->getDataUsing('points', 'point_id', 'street');
    }

    public function getScootersInfo()
    {
        return $this->getDataUsing('scooters', 'scooter_id', 'num');
    }

    public function getClientsInfo()
    {
        return $this->getDataUsing('users', 'user_id', 'name');
    }

    private function getDataUsing(string $table, string $orders_key, string $foreign_column)
    {
        return Order::select($table . '.' . $foreign_column, DB::raw('count(orders.id) as count'))
            ->join($table, $table . '.id', 'orders.' . $orders_key)
            ->groupBy($table . '.id')
            ->get();
    }

    public function getOrdersByUser(string $id)
    {
        $this->cancelOldOrders();

        $orders = Order::orderBy('date')
            ->where('user_id', '=', $id)
            ->whereIn('status', [OrderRepository::COMPLETED, OrderRepository::CANCELLED])
            ->get();
        foreach ($orders as &$order) {
            $order->status = $this->statuses[$order->status];
        }

        return $orders;
    }

    public function getActiveOrder(string $userId)
    {
        $order = Order::where('user_id', '=', $userId)
            ->whereNotIn('status', [OrderRepository::COMPLETED, OrderRepository::CANCELLED])
            ->get()->first();
        if ($order != null) {
            $order->status = $this->statuses[$order->status];
        }

        return $order ?: null;
    }

    public function thereIsNoActiveOrders() : bool
    {
        return !Order::where('user_id', '=', Auth::id())
            ->whereNotIn('status', [OrderRepository::COMPLETED, OrderRepository::CANCELLED])
            ->exists();
    }

    public function storeOrder(array $inputs) : void
    {
        $order = new Order();
        $order->point_id = $inputs['point'];
        $order->scooter_id = $inputs['scooter'];
        $order->user_id = Auth::id();
        $order->status = OrderRepository::PENDING;
        $order->save();
    }

    public function updateOrder(Order $order, array $inputs) : void
    {
        $order->manager_id = Auth::id();
        $order->price = $inputs['price'];
        $order->status = $inputs['status'];
        $order->collateral = $inputs['collateral'];
        $order->save();
    }

    public function cancelOrder(Order $order) : void
    {
        $order->status = OrderRepository::CANCELLED;
        $order->save();
    }

}
