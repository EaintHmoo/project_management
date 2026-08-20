<?php

namespace App\Modules\Meetings\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Meetings\Application\Services\CancelMeetingService;
use App\Modules\Meetings\Application\Services\CreateMeetingService;
use App\Modules\Meetings\Application\Services\DeleteMeetingService;
use App\Modules\Meetings\Application\Services\RespondToMeetingService;
use App\Modules\Meetings\Application\Services\UpdateMeetingService;
use App\Modules\Meetings\Domain\Contracts\MeetingRepositoryInterface;
use App\Modules\Meetings\Domain\DTOs\CreateMeetingData;
use App\Modules\Meetings\Domain\Enums\ParticipantResponse;
use App\Modules\Meetings\Domain\Models\Meeting;
use App\Modules\Meetings\Presentation\Http\Requests\CreateMeetingRequest;
use App\Modules\Meetings\Presentation\Http\Requests\RespondToMeetingRequest;
use App\Modules\Meetings\Presentation\Http\Requests\UpdateMeetingRequest;
use App\Modules\Meetings\Presentation\Http\Resources\MeetingResource;
use App\Modules\Tenancy\Domain\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function __construct(
        private readonly MeetingRepositoryInterface $meetings,
    ) {}

    public function index(Request $request, Organization $organization): JsonResponse
    {
        $this->authorize('viewAny', [Meeting::class, $organization]);

        $meetings = $this->meetings->forOrganization($organization, $request->input('from'), $request->input('to'));

        return response()->json([
            'success' => true,
            'data' => MeetingResource::collection($meetings),
            'message' => 'Meetings loaded.',
        ]);
    }

    public function store(CreateMeetingRequest $request, Organization $organization, CreateMeetingService $service): JsonResponse
    {
        $this->authorize('create', [Meeting::class, $organization]);

        $meeting = $service->execute(new CreateMeetingData(
            organizationId: $organization->id,
            hostId: $request->user()->id,
            title: $request->string('title')->toString(),
            description: $request->input('description'),
            startsAtUtc: $request->string('starts_at')->toString(),
            endsAtUtc: $request->string('ends_at')->toString(),
            timezone: $request->string('timezone')->toString(),
            recurrenceRule: $request->input('recurrence_rule'),
            participantIds: $request->input('participant_ids', []),
        ));

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting),
            'message' => 'Meeting scheduled successfully.',
        ], 201);
    }

    public function show(Organization $organization, Meeting $meeting): JsonResponse
    {
        $this->authorize('view', $meeting);

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting->load('participants')),
            'message' => 'Meeting loaded.',
        ]);
    }

    public function update(UpdateMeetingRequest $request, Organization $organization, Meeting $meeting, UpdateMeetingService $service): JsonResponse
    {
        $this->authorize('update', $meeting);

        $meeting = $service->execute($meeting, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting),
            'message' => 'Meeting updated successfully.',
        ]);
    }

    public function respond(RespondToMeetingRequest $request, Organization $organization, Meeting $meeting, RespondToMeetingService $service): JsonResponse
    {
        $this->authorize('respond', $meeting);

        $meeting = $service->execute(
            $meeting,
            $request->user()->id,
            ParticipantResponse::from($request->string('response')->toString()),
        );

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting),
            'message' => 'Response recorded.',
        ]);
    }

    public function cancel(Organization $organization, Meeting $meeting, CancelMeetingService $service): JsonResponse
    {
        $this->authorize('cancel', $meeting);

        $meeting = $service->execute($meeting);

        return response()->json([
            'success' => true,
            'data' => new MeetingResource($meeting),
            'message' => 'Meeting cancelled.',
        ]);
    }

    public function destroy(Organization $organization, Meeting $meeting, DeleteMeetingService $service): JsonResponse
    {
        $this->authorize('delete', $meeting);

        $service->execute($meeting);

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Meeting deleted successfully.',
        ]);
    }
}
