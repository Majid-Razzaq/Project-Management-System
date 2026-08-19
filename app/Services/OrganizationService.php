<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationService
{
    /**
     * Create a new class instance.
     */
    public function create(User $user, array $data): Organization
    {
        return DB::transaction(function () use ($user, $data) {
            $slug = $this->generateUniqueSlug($data['name']);

            $organization = Organization::create([
                'owner_id' => $user->id,
                'name' => $data['name'],
                'slug' => $slug,
                'description' => $data['description'] ?? null,
            ]);

            $organization->users()->attach($user->id, [
                'role' => 'owner',
            ]);

            return $organization;
        });
    }

    public function update(
        Organization $organization,
        array $data
    ): Organization {
        return DB::transaction(function () use ($organization, $data) {
            $organization->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            return $organization->refresh();
        });
    }

    /**
     * Delete an organization.
     */
    public function delete(Organization $organization): void
    {
        DB::transaction(function () use ($organization) {
            $organization->delete();
        });
    }

    /**
     * Generate a unique organization slug.
     */
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Organization::where('slug', $slug)->exists()) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
