<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderController extends Controller
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    public function __construct()
    {
        $this->orderRepository = app(OrderRepository::class);
    }

    public function store(StoreOrderRequest $request)
    {
        if ($this->orderRepository->thereIsNoActiveOrders()) {
            $inputs = $request->validated();
            $this->orderRepository->storeOrder($inputs);
            return redirect()->route('dashboard');
        }
    }

    public function edit(Order $order)
    {
        return view('app.orders.editor', [
            'order' => $order,
            'statuses' => $this->orderRepository->getStatuses()
        ]);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $inputs = $request->validated();
        $this->orderRepository->updateOrder($order, $inputs);
        return redirect()->route('dashboard');
    }

    public function destroy(Order $order)
    {
        $this->orderRepository->cancelOrder($order);
        return redirect()->route('dashboard');
    }
}
