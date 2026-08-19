<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $organization->name }} — Members
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Success message --}}
            <div id="success-message" class="hidden mb-4 p-4 bg-green-100 text-green-800 rounded"></div>

            {{-- Error message --}}
            <div id="error-message" class="hidden mb-4 p-4 bg-red-100 text-red-800 rounded"></div>

            {{-- Add member --}}
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">

                <h3 class="text-lg font-semibold mb-4">
                    Add Member
                </h3>

                <form id="add-member-form" data-organization-id="{{ $organization->id }}">

                    @csrf

                    <div class="mb-4">

                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            User ID
                        </label>

                        <input type="number" id="user_id" name="user_id"
                            class="mt-1 block w-full border-gray-300 rounded-md" required>

                    </div>

                    <div class="mb-4">

                        <label for="role" class="block text-sm font-medium text-gray-700">
                            Role
                        </label>

                        <select id="role" name="role" class="mt-1 block w-full border-gray-300 rounded-md">
                            <option value="member">Member</option>
                            <option value="admin">Admin</option>
                        </select>

                    </div>

                    <button type="submit" id="add-member-button" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Add Member
                    </button>

                </form>

            </div>

            {{-- Members --}}
            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Organization Members
                </h3>

                <div id="members-list">

                    @foreach ($members as $member)
                        <div class="member-row border-b py-4 flex justify-between items-center"
                            data-user-id="{{ $member->id }}">

                            <div>
                                <div class="font-semibold">
                                    {{ $member->name }}
                                </div>

                                <div class="text-sm text-gray-500">
                                    {{ $member->email }}
                                </div>
                            </div>

                            <div class="flex gap-2 items-center">

                                @if ($organization->owner_id === $member->id)
                                    <span class="px-3 py-1 bg-gray-200 rounded">
                                        Owner
                                    </span>
                                @else
                                    <select class="member-role" data-user-id="{{ $member->id }}">
                                        <option value="member">
                                            Member
                                        </option>

                                        <option value="admin">
                                            Admin
                                        </option>
                                    </select>

                                    <button type="button" class="remove-member px-3 py-1 bg-red-600 text-white rounded"
                                        data-user-id="{{ $member->id }}">
                                        Remove
                                    </button>
                                @endif

                            </div>

                        </div>
                    @endforeach

                </div>

                <div class="mt-4">
                    {{ $members->links() }}
                </div>

            </div>

        </div>

    </div>

    @vite('resources/js/organization-members.js')

</x-app-layout>
