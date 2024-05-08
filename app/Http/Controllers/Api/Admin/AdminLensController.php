<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\LensService;
use App\Http\Requests\StoreLensRequest;
use App\Http\Resources\AdminLensIndexResource;
use App\Http\Resources\AdminLensShowResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;




class AdminLensController extends Controller
{
    protected $lensService;

    public function __construct(LensService $lensService)
    {
        $this->lensService = $lensService;
    }

    public function index(): AnonymousResourceCollection
    {
        $lenses = $this->lensService->listLenses();
        return AdminLensIndexResource::collection($lenses);
    }

    public function store(StoreLensRequest $request): AdminLensShowResource
    {

        $lens = $this->lensService->createLensWithPrices($request->validated());
        return new AdminLensShowResource($lens->load('prices.currency'));
    }
}

