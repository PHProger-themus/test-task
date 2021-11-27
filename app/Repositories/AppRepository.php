<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AppRepository
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
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('scooters', 'orders.scooter_id', '=', 'scooters.id')
            ->join('points', 'orders.point_id', '=', 'points.id')
            ->select(['orders.id', 'name', 'street', 'num', 'manager_id', 'price', 'status', 'collateral', 'date'])
            ->orderBy('id')
            ->get();

        foreach ($orders as &$order) {
            $manager = DB::table('users')->select(['name'])->where('id', '=', $order->manager_id)->get()->first();
            $order->manager = $manager->name ?? "-";
            $order->status = $this->statuses[$order->status];
        }

        return $orders;
    }

    private function cancelOldOrders()
    {
        $currentTimestamp = Carbon::now()->toDateTimeString();
        DB::table('orders')
            ->whereRaw("date < NOW() - INTERVAL '15 minutes'")
            ->where('status', '=', self::PENDING)
            ->update([
                'status' => self::CANCELLED
            ]);
    }

    public function getStatuses() {
        return $this->statuses;
    }

    public function getManagersInfo(): array
    {
        return DB::select(DB::raw("SELECT COUNT(orders.id), name FROM orders JOIN users ON orders.manager_id = users.id GROUP BY users.id"));
    }

    public function getPointsInfo(): array
    {
        return DB::select(DB::raw("SELECT COUNT(orders.id), street FROM orders JOIN points ON orders.point_id = points.id GROUP BY points.id"));
    }

    public function getScootersInfo(): array
    {
        return DB::select(DB::raw("SELECT COUNT(orders.id), num FROM orders JOIN scooters ON orders.scooter_id = scooters.id GROUP BY scooters.id"));
    }

    public function getClientsInfo(): array
    {
        return DB::select(DB::raw("SELECT COUNT(orders.id), name FROM orders JOIN users ON orders.user_id = users.id GROUP BY users.id"));
    }

    public function getScooters(): \Illuminate\Support\Collection
    {
        return DB::table('scooters')
            ->select(['scooters.id as id', 'num', 'booked_by', 'booked_at', 'street'])
            ->join('points', 'scooters.point_id', '=', 'points.id')
            ->orderBy('id')
            ->get();
    }

    public function getScooter(string $id)
    {
        return DB::table('scooters')
            ->select(['id', 'num', 'point_id'])
            ->where('id', '=', $id)
            ->get()->first();
    }

    public function getPoints(): \Illuminate\Support\Collection
    {
        return DB::table('points')
            ->orderBy('id')
            ->get();
    }

    public function getPoint(string $id)
    {
        return DB::table('points')
            ->where('id', '=', $id)
            ->get()->first();
    }

    public function getUsers(): \Illuminate\Support\Collection
    {
        return DB::table('users')
            ->select(['id', 'name', 'email', 'role', 'created_at'])
            ->orderBy('id')
            ->get();
    }

    public function getUser(string $id)
    {
        return DB::table('users')
            ->select(['id', 'name', 'email', 'password', 'role'])
            ->where('id', '=', $id)
            ->get()->first();
    }

    public function getOrderInformation(string $id)
    {
        return DB::table('orders')
            ->select(['id', 'price', 'status', 'collateral'])
            ->where('id', '=', $id)
            ->get()->first();
    }

    public function getOrdersByUser(string $id)
    {
        $this->cancelOldOrders();
        $orders = DB::table('orders')
            ->leftJoin('users', 'orders.manager_id', '=', 'users.id')
            ->join('scooters', 'orders.scooter_id', '=', 'scooters.id')
            ->join('points', 'orders.point_id', '=', 'points.id')
            ->select(['orders.id as id', 'name', 'street', 'num', 'price', 'status', 'date'])
            ->where('user_id', '=', $id)
            ->whereIn('status', [self::COMPLETED, self::CANCELLED])
            ->orderBy('date')
            ->get();

        foreach ($orders as &$order) {
            $order->status = $this->statuses[$order->status];
        }
        return $orders;
    }

    public function getActiveOrder(string $userId)
    {
        $order = DB::table('orders')
            ->leftJoin('users', 'orders.manager_id', '=', 'users.id')
            ->join('scooters', 'orders.scooter_id', '=', 'scooters.id')
            ->join('points', 'orders.point_id', '=', 'points.id')
            ->select(['orders.id as id', 'name', 'street', 'num', 'price', 'status', 'date'])
            ->where('user_id', '=', $userId)
            ->whereNotIn('status', [self::COMPLETED, self::CANCELLED])
            ->get()->first();

        if ($order != null) {
            $order->status = $this->statuses[$order->status];
        }
        return $order ?: null;
    }

}
