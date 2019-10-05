<?php

declare(strict_types=1);

namespace App\Http\Controllers\Journal;

use App\Domain\Journal\Dao\JournalScheduleDao as AppJournalScheduleDao;
use App\Domain\Journal\Dto\JournalSchedule;
use App\Domain\Journal\Service\JournalScheduleSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleApiController extends Controller
{
    private $dao;
    private $saveService;

    public function __construct(AppJournalScheduleDao $dao, JournalScheduleSaveService $saveService)
    {
        $this->dao = $dao;
        $this->saveService = $saveService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return ['data' => $this->dao->all(), 'message' => 'OK'];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request): array
    {
        $schedule = JournalSchedule::fromRequest($request->all());
        $this->saveService->create($schedule);
        return ['message' => 'OK'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     */
    public function update(Request $request, int $id): array
    {
        $schedule = JournalSchedule::fromRequest($request->all());
        $this->saveService->update($schedule);
        return ['message' => 'OK'];
    }
}
