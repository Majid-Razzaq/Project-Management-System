<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Organizations
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    My Organizations
                </h3>

                @forelse ($organizations as $organization)

                    <div class="border-b py-4 flex justify-between items-center">

                        <div>
                            <h4 class="font-semibold">
                                {{ $organization->name }}
                            </h4>

                            <p class="text-sm text-gray-500">
                                {{ $organization->description }}
                            </p>
                        </div>

                        <a
                            href="{{ route('organizations.show', $organization) }}"
                            class="text-blue-600 hover:underline"
                        >
                            View
                        </a>

                    </div>

                @empty

                    <p class="text-gray-500">
                        You don't belong to any organizations yet.
                    </p>

                @endforelse

                <div class="mt-4">
                    {{ $organizations->links() }}
                </div>

            </div>

        </div>
    </div>

</x-app-layout>