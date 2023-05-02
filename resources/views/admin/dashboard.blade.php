<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kezdőoldal') }}
        </h2>
    </x-slot>

<div class="container justify-center">
    <div class="my-10">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900 text-lg">
                    {{ session('company_name') }}
                </div>
            </div>
            <div class="my-7">
                <a href="{{ url('welcome') }}"
                   class="bg-gray-200 p-4 rounded-lg hover:bg-gray-300 transition duration-300 ease-in-out">
                    Másik cég választása
                </a>
            </div>
        </div>
    </div>
    <div class="max-w-7xl">
        <div id="calendar" class="p-4"></div>
    </div>
</div>
</x-app-layout>
<script type="module" src="{{ mix('resources/js/app.mjs') }}"></script>
