<?php

namespace App\Http\Controllers;

use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Http\Resources\Campaign\CampaignCollection;
use App\Http\Resources\Campaign\CampaignResource;
use App\Http\Resources\Message\MessageCollection;
use App\Models\Campaign;
use App\Services\Campaign\CampaignService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CampaignController extends Controller
{
    public function __construct(
        private CampaignService $campaignService
    ) {}

    public function index()
    {
        return Response::success(
            new CampaignCollection($this->campaignService->getAll(auth()->user())),
            'Campaigns retrieved successfully'
        );
    }

    public function store(StoreCampaignRequest $request)
    {
        Gate::authorize('create', Campaign::class);

        return Response::success(
            new CampaignResource($this->campaignService->store(auth_user(), $request->validated())),
            'Campaign created successfully',
            201
        );
    }

    public function show(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        return Response::success(
            new CampaignResource($this->campaignService->getSingle($campaign)),
            'Campaign retrieved successfully',
        );
    }

    public function campaignMessages(Campaign $campaign)
    {
        Gate::authorize('view', $campaign);

        return Response::success(
            new MessageCollection($this->campaignService->getCampaignMessages($campaign)),
            'Campaign retrieved successfully',
        );
    }

    public function update(UpdateCampaignRequest $request, Campaign $campaign)
    {
        Gate::authorize('update', $campaign);

        return Response::success(
            new CampaignResource($this->campaignService->update($campaign, $request->validated())),
            'Campaign updated successfully',
        );
    }

    public function destroy(Campaign $campaign)
    {
        Gate::authorize('delete', $campaign);
        $this->campaignService->delete($campaign);
        return Response::noContent();
    }
}
