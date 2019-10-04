<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dto\AccountTitle;
use App\Domain\Account\Service\PlAccountSaveService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlAccountApiController extends Controller
{
    /**
     * @var PlAccountSaveService
     */
    private $saveService;

    public function __construct(PlAccountSaveService $saveService)
    {
        $this->saveService = $saveService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): array
    {
        $a = AccountTitle::fromRequest($request->plAccount);
        $this->saveService->create($a);
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
        $a = AccountTitle::fromRequest($request->plAccount);
        $this->saveService->update($a);
        return ['messsage' => 'OK'];
    }
}
