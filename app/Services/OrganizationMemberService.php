<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use RuntimeException;

class OrganizationMemberService
{
    public function addMember(
        Organization $organization,
        User $user,
        string $role
    ): void {
        if ($organization->owner_id === $user->id) {
            throw new RuntimeException(
                'The organization owner cannot be added as a member.'
            );
        }

        $alreadyMember = $organization->users()
            ->whereKey($user->id)
            ->exists();

        if ($alreadyMember) {
            throw new RuntimeException(
                'User is already a member of this organization.'
            );
        }

        $organization->users()->attach($user->id, [
            'role' => $role,
        ]);
    }

    public function updateRole(
        Organization $organization,
        User $user,
        string $role
    ): void {
        if ($organization->owner_id === $user->id) {
            throw new RuntimeException(
                'The organization owner role cannot be changed.'
            );
        }

        $isMember = $organization->users()
            ->whereKey($user->id)
            ->exists();

        if (!$isMember) {
            throw new RuntimeException(
                'User is not a member of this organization.'
            );
        }

        $organization->users()->updateExistingPivot(
            $user->id,
            ['role' => $role]
        );
    }

    public function removeMember(
        Organization $organization,
        User $user
    ): void {
        if ($organization->owner_id === $user->id) {
            throw new RuntimeException(
                'The organization owner cannot be removed.'
            );
        }

        $organization->users()->detach($user->id);
    }
}
