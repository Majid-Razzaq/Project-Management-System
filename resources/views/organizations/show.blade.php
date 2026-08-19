<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $organization->name }}
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

                <h1 class="text-2xl font-bold">
                    {{ $organization->name }}
                </h1>

                <p class="mt-2 text-gray-600">
                    {{ $organization->description }}
                </p>

                <div class="mt-6">

                    <h3 class="font-semibold">
                        Owner
                    </h3>

                    <p>
                        {{ $organization->owner->name }}
                    </p>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>