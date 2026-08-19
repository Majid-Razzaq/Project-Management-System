<?php

namespace App\Http\Requests;

use App\Models\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddOrganizationMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        $organization = $this->route('organization');

        return $organization instanceof Organization
            && $this->user()?->can('manageMembers', $organization);
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::notIn([
                    $this->route('organization')?->owner_id,
                ]),
            ],

            'role' => [
                'required',
                'string',
                Rule::in([
                    'admin',
                    'member',
                ]),
            ],
        ];
    }
}
