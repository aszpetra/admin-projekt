<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kezdőoldal') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="my-10">
            <div class="max-w-7xl sm:px-6 lg:px-8">

            </div>
            <div class="container">
                <div id="calendar" class="p-4"></div>
            </div>
        </div>
    </div>
</x-app-layout>
<script type="module" src="{{ mix('resources/js/home.mjs') }}"></script>
