<?php

namespace App\Http\Controllers;

use App\Repositories\AppRepository;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PointController extends Controller
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
        return view('app.points.index', [
            'points' => $this->appRepository->getPoints()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('app.points.editor', [
            'mode' => self::CREATE
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
            'street' => 'required|unique:points,street'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('points')->insert([
            'street' => $request->post('street')
        ]);
        return redirect()->route('points.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id)
    {
        return view('app.points.editor', [
            'mode' => self::EDIT,
            'point' => $this->appRepository->getPoint($id)
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
            'street' => 'required|unique:points,street,' . $id
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        DB::table('points')->where('id', '=', $id)->update([
            'street' => $request->post('street'),
        ]);
        return redirect()->route('points.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id)
    {
        try {
            DB::table('points')->where('id', '=', $id)
                ->delete();
            return redirect()->route('points.index');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Ошибка удаления записи']);
        }
    }
}
