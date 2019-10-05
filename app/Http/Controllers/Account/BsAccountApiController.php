<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Journal\Service\BsAccountSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BsAccountApiController extends Controller
{
    /**
     * @var BsAccountSaveService
     */
    private $saveService;

    public function __construct(BsAccountSaveService $saveService)
    {
        $this->saveService = $saveService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): array
    {
        $a = AccountTitle::fromRequest($request->bsAccount);
        $this->saveService->create($a, $request->openingBalance);
        return ['messsage' => 'OK'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     */
    public function update(Request $request, int $id): array
    {
        $a = AccountTitle::fromRequest($request->bsAccount);
        $this->saveService->update($a, $request->openingBalance);
        return ['messsage' => 'OK'];
    }
}
