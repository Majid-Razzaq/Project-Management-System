<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrganizationRequest;
use App\Http\Requests\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Services\OrganizationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class OrganizationController extends Controller
{
    public function __construct(
        private readonly OrganizationService $organizationService
    ) {}

    public function index(Request $request): View
    {
        $organizations = $request->user()
            ->organizations()
            ->latest('organizations.created_at')
            ->paginate(10);

        return view('organizations.index', compact('organizations'));
    }

    public function store(
        StoreOrganizationRequest $request
    ): RedirectResponse {
        $this->organizationService->create(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function show(Organization $organization): View
    {
        Gate::authorize('view', $organization);

        return view('organizations.show', compact('organization'));
    }

    /**
     * Update an organization.
     */
    public function update(
        UpdateOrganizationRequest $request,
        Organization $organization
    ): RedirectResponse {
        Gate::authorize('update', $organization);

        $this->organizationService->update(
            $organization,
            $request->validated()
        );

        return redirect()
            ->route('organizations.show', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Delete an organization.
     */
    public function destroy(
        Request $request,
        Organization $organization
    ): RedirectResponse {
        Gate::authorize('delete', $organization);

        $this->organizationService->delete($organization);

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }
}
