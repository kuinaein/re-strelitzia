<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Domain\Account\Dao\AccountTitleDao;
use App\Http\Controllers\Controller;

class AccountApiController extends Controller
{
    /**
     * @var AccountTitleDao
     */
    private $dao;

    public function __construct(AccountTitleDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): array
    {
        return ['data' => $this->dao->all(), 'message' => 'OK'];
    }
}
