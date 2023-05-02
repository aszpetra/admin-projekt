<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszak beosztások') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <div class="py-7 flex justify-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @foreach($employees as $data)
                    <div class="m-3 p-2 bg-gray-200 rounded-lg flex justify-center">
                        {{$data->name}}
                    </div>
                @endforeach
                <div class="max-w-7xl mx-auto flex justify-center">
                    <x-secondary-button class="m-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                        onClick="window.location='{{ URL::route('shift_employee.index') }}'">
                        {{ __('Vissza') }}
                    </x-secondary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

