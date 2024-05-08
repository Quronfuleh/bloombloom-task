<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\LensService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use App\Http\Requests\UserLensIndexRequest;
use App\Http\Resources\UserLensIndexResource;






class UserLensController extends Controller
{
    protected $lensService;

    public function __construct(LensService $lensService)
    {
        $this->lensService = $lensService;
    }

    public function index(UserLensIndexRequest $request): AnonymousResourceCollection
{
    $currencyCode = $request->header('X-Currency', 'USD');
    $lenses = $this->lensService->getAllLensesWithCurrency($currencyCode);
    return UserLensIndexResource::collection($lenses);
}

}

