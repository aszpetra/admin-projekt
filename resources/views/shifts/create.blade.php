<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszakok') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('shifts.store') }}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="company_id">Cég:</label><br>
                    <select name="company_id" id="company_id" class="pr-10 py-3 rounded-lg">
                        @foreach($companies as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select><br>
                    <label class="pl-2" for="name">Név</label><br>
                    <input class="pr-10 py-3 rounded-lg" type="text" name="name" placeholder="pl.: Húsfeldolgozó éjszakai" value="{{old('name')}}"><br>
                    <label class="pl-2" for="work_hours">Munkaóra</label><br>
                    <input class="pr-10 py-3 rounded-lg" type="number" name="work_hours" placeholder="Munkaóra" value="{{old('work_hours')}}"><br>
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                onClick="window.location='{{ URL::route('shifts.index') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>

        </form>
    </div>
</x-app-layout>
