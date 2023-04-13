<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kezdőoldal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex justify-center">
                <div class="p-6 text-gray-900">
                    {{ __("Üdvözöljük!") }}
                </div>
            </div>
            <div class="my-7">
                <a href="{{ url('welcome') }}"
                   class="bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    Másik cég választása
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
