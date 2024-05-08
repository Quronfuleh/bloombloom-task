<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\FrameService;
use App\Http\Requests\StoreFrameRequest;
use App\Http\Resources\AdminFrameIndexResource;
use App\Http\Resources\AdminFrameShowResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;




class AdminFrameController extends Controller
{
    protected $frameService;

    public function __construct(FrameService $frameService)
    {
        $this->frameService = $frameService;
    }

    public function index(): AnonymousResourceCollection
    {
        $frames = $this->frameService->listFrames();
        return AdminFrameIndexResource::collection($frames);
    }
    

    public function store(StoreFrameRequest $request): AdminFrameShowResource
    {
        $frame = $this->frameService->createFrameWithPrices($request->validated());
        return new AdminFrameShowResource($frame->load('prices.currency'));
    }
}
