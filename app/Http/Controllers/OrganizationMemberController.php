<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddOrganizationMemberRequest;
use App\Http\Requests\UpdateOrganizationMemberRequest;
use App\Models\Organization;
use App\Models\User;
use App\Services\OrganizationMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use RuntimeException;

class OrganizationMemberController extends Controller
{
    public function __construct(
        private readonly OrganizationMemberService $memberService
    ) {}

    public function index(
        Request $request,
        Organization $organization
    ): View {
        Gate::authorize('view', $organization);

        $members = $organization
            ->users()
            ->select('users.id', 'users.name', 'users.email')
            ->orderBy('users.name')
            ->paginate(20);

        return view(
            'organizations.members.index',
            compact('organization', 'members')
        );
    }

    public function store(
        AddOrganizationMemberRequest $request,
        Organization $organization
    ): JsonResponse {
        Gate::authorize('manageMembers', $organization);

        $user = User::findOrFail(
            $request->validated('user_id')
        );

        try {
            $this->memberService->addMember(
                $organization,
                $user,
                $request->validated('role')
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Member added successfully.',
        ], 201);
    }

    public function update(
        UpdateOrganizationMemberRequest $request,
        Organization $organization,
        User $user
    ): JsonResponse {
        Gate::authorize('manageMembers', $organization);

        try {
            $this->memberService->updateRole(
                $organization,
                $user,
                $request->validated('role')
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Member role updated successfully.',
        ]);
    }

    public function destroy(
        Request $request,
        Organization $organization,
        User $user
    ): JsonResponse {
        Gate::authorize('manageMembers', $organization);

        try {
            $this->memberService->removeMember(
                $organization,
                $user
            );
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Member removed successfully.',
        ]);
    }
}
