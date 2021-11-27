<?php

namespace App\Http\Controllers;

use App\Repositories\AppRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ScooterController extends Controller
{

    private $appRepository;
    private const CREATE = 0;
    private const EDIT = 1;

    public function __construct()
    {
        $this->appRepository = app(AppRepository::class);
    }

    public function index()
    {
        return view('app.scooters.index', [
            'scooters' => $this->appRepository->getScooters()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('app.scooters.editor', [
            'mode' => self::CREATE,
            'points' => $this->appRepository->getPoints()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $rules = [
            'num' => 'required|unique:scooters,num',
            'point' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('scooters')->insert([
            'num' => $request->post('num'),
            'point_id' => $request->post('point'),
        ]);
        return redirect()->route('scooters.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        return view('app.scooters.editor', [
            'mode' => self::EDIT,
            'scooter' => $this->appRepository->getScooter($id),
            'points' => $this->appRepository->getPoints()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'num' => 'required|unique:scooters,num,' . $id,
            'point' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('scooters')->where('id', '=', $id)->update([
            'num' => $request->post('num'),
            'point_id' => $request->post('point'),
        ]);
        return redirect()->route('scooters.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        try {
            DB::table('scooters')->where('id', '=', $id)
                ->delete();
            return redirect()->route('scooters.index');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Ошибка удаления записи']);
        }
    }
}
