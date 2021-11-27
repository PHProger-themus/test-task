<?php

namespace App\Http\Controllers;

use App\Repositories\AppRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * @var AppRepository
     */
    private $appRepository;

    public function __construct()
    {
        $this->appRepository = app(AppRepository::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request)
    {
        if (!Auth::user()->role) {
            $activeExists = DB::table('orders')
                ->where('user_id', '=', Auth::id())
                ->whereNotIn('status', [AppRepository::COMPLETED, AppRepository::CANCELLED])
                ->exists();
            if (!$activeExists) {

                $rules = [
                    'point' => 'required|numeric',
                    'scooter' => 'required|numeric',
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                DB::table('orders')->insert([
                    'point_id' => $request->post('point'),
                    'scooter_id' => $request->post('scooter'),
                    'user_id' => Auth::id(),
                    'status' => AppRepository::PENDING
                ]);
                return redirect()->route('dashboard');
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
        if (Auth::user()->role == 2) {
            return view('app.orders.editor', [
                'order' => $this->appRepository->getOrderInformation($id),
                'statuses' => $this->appRepository->getStatuses()
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
                'price' => 'required|numeric',
                'status' => 'required',
                'collateral' => 'required',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            DB::table('orders')->where('id', '=', $id)->update([
                'manager_id' => Auth::id(),
                'price' => $request->post('price'),
                'status' => $request->post('status'),
                'collateral' => $request->post('collateral'),
            ]);
            return redirect()->route('dashboard');
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
        if (!Auth::user()->role) {
            DB::table('orders')->where('id', '=', $id)
                ->update([
                    'status' => AppRepository::CANCELLED
                ]);
            return redirect()->route('dashboard');
        } else {
            return back();
        }
    }
}
