<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Services\FrameService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;
use App\Http\Requests\UserFrameIndexRequest;
use App\Http\Resources\UserFrameIndexResource;




class UserFrameController extends Controller
{
    protected $frameService;

    public function __construct(FrameService $frameService)
    {
        $this->frameService = $frameService;
    }


    public function index(UserFrameIndexRequest $request)
    {
        $currencyCode = $request->header('X-Currency');
        $frames = $this->frameService->getActiveFramesWithCurrency($currencyCode);
        return UserFrameIndexResource::collection($frames);
    }

}
