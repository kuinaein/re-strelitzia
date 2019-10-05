<?php

declare(strict_types=1);

namespace App\Http\Controllers\Journal;

use App\Domain\Journal\Dto\AccountingJournal;
use App\Domain\Journal\Service\AccountingJournalSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JournalApiController extends Controller
{
    /**
     * @var AccountingJournalSaveService
     */
    private $saveService;

    public function __construct(AccountingJournalSaveService $saveService)
    {
        $this->saveService = $saveService;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     */
    public function store(Request $request): array
    {
        $j = AccountingJournal::fromRequest($request->all());
        $saved = $this->saveService->create($j);
        return ['data' => $saved, 'messsage' => 'OK'];
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int     $journalId
     */
    public function update(Request $request, int $journalId): array
    {
        $j = AccountingJournal::fromRequest($request->all());
        $this->saveService->update($j);
        return ['messsage' => 'OK'];
    }

    /**
     *  Remove the specified resource from storage.
     * @param int $journalId
     */
    public function destroy(int $journalId): array
    {
        $this->saveService->destroy($journalId);
        return ['messsage' => 'OK'];
    }
}
