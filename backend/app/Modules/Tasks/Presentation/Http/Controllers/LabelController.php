<?php

namespace App\Modules\Tasks\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Tasks\Domain\Models\Label;
use App\Modules\Tasks\Presentation\Http\Requests\CreateLabelRequest;
use App\Modules\Tasks\Presentation\Http\Resources\LabelResource;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\JsonResponse;

class LabelController extends Controller
{
    public function index(Organization $organization): JsonResponse
    {
        $this->authorize('viewAny', [Label::class, $organization]);

        return response()->json([
            'success' => true,
            'data' => LabelResource::collection($organization->labels()->get()),
            'message' => 'Labels loaded.',
        ]);
    }

    public function store(CreateLabelRequest $request, Organization $organization): JsonResponse
    {
        $this->authorize('manage', [Label::class, $organization]);

        $label = Label::create([
            'organization_id' => $organization->id,
            'name' => $request->string('name')->toString(),
            'color' => $request->string('color')->toString(),
        ]);

        return response()->json([
            'success' => true,
            'data' => new LabelResource($label),
            'message' => 'Label created successfully.',
        ], 201);
    }

    public function destroy(Organization $organization, Label $label): JsonResponse
    {
        $this->authorize('delete', $label);

        $label->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Label deleted successfully.',
        ]);
    }
}
