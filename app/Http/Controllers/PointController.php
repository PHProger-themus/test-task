<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePointRequest;
use App\Http\Requests\UpdatePointRequest;
use App\Models\Point;
use App\Repositories\PointRepository;
use Illuminate\Database\QueryException;

class PointController extends Controller
{

    /**
     * @var PointRepository
     */
    private $pointRepository;
    private const CREATE = 0;
    private const EDIT = 1;

    public function __construct()
    {
        $this->pointRepository = app(PointRepository::class);
    }

    public function index()
    {
        return view('app.points.index', [
            'points' => $this->pointRepository->getPoints()
        ]);
    }

    public function create()
    {
        return view('app.points.editor', [
            'mode' => PointController::CREATE
        ]);
    }

    public function store(StorePointRequest $request)
    {
        $inputs = $request->validated();
        $this->pointRepository->storePoint($inputs);
        return redirect()->route('points.index');
    }

    public function edit(Point $point)
    {
        return view('app.points.editor', [
            'mode' => PointController::EDIT,
            'point' => $point
        ]);
    }

    public function update(UpdatePointRequest $request, Point $point)
    {
        $inputs = $request->validated();
        $this->pointRepository->updatePoint($point, $inputs);
        return redirect()->route('points.index');
    }

    public function destroy(Point $point)
    {
        try {
            $this->pointRepository->destroyPoint($point);
            return redirect()->route('points.index');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Ошибка удаления записи']);
        }
    }
}
