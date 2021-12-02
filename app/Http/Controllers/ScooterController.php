<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScooterRequest;
use App\Http\Requests\UpdateScooterRequest;
use App\Models\Scooter;
use App\Repositories\PointRepository;
use App\Repositories\ScooterRepository;
use Illuminate\Database\QueryException;

class ScooterController extends Controller
{

    /**
     * @var ScooterRepository
     */
    private $scooterRepository;
    /**
     * @var PointRepository
     */
    private $pointRepository;
    private const CREATE = 0;
    private const EDIT = 1;

    public function __construct()
    {
        $this->scooterRepository = app(ScooterRepository::class);
        $this->pointRepository = app(PointRepository::class);
    }

    public function index()
    {
        return view('app.scooters.index', [
            'scooters' => $this->scooterRepository->getScooters()
        ]);
    }

    public function create()
    {
        return view('app.scooters.editor', [
            'mode' => ScooterController::CREATE,
            'points' => $this->pointRepository->getPoints()
        ]);
    }

    public function store(StoreScooterRequest $request)
    {
        $inputs = $request->validated();
        $this->scooterRepository->storeScooter($inputs);
        return redirect()->route('scooters.index');
    }

    public function edit(Scooter $scooter)
    {
        return view('app.scooters.editor', [
            'mode' => ScooterController::EDIT,
            'scooter' => $scooter,
            'points' => $this->pointRepository->getPoints()
        ]);
    }

    public function update(UpdateScooterRequest $request, Scooter $scooter)
    {
        $inputs = $request->validated();
        $this->scooterRepository->updateScooter($scooter, $inputs);
        return redirect()->route('scooters.index');
    }

    public function destroy(Scooter $scooter)
    {
        try {
            $this->scooterRepository->destroyScooter($scooter);
            return redirect()->route('scooters.index');
        } catch (QueryException $e) {
            return back()->withErrors(['error' => 'Ошибка удаления записи']);
        }
    }
}
