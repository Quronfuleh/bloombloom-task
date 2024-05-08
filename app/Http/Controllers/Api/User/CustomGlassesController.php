<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCustomGlassesRequest;
use App\Services\CustomGlassesService;
use App\Http\Resources\CustomGlassesResource;

class CustomGlassesController extends Controller
{
    protected $customGlassesService;

    public function __construct(CustomGlassesService $customGlassesService)
    {
        $this->customGlassesService = $customGlassesService;
    }

    public function store(CreateCustomGlassesRequest $request): CustomGlassesResource
    {
        $data = $this->customGlassesService->createCustomGlasses(
            $request->frame_id, 
            $request->lens_id, 
            $request->currency_code
        );
    
        return new CustomGlassesResource((object)$data);
    }
    
}
