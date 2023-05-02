<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hiányzások') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('holiday_store') }}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="type">Hiányzás típusa</label><br>
                    <select name="type" id="type" class="pr-10 py-3 rounded-lg">
                        <option value="holiday">Fizetett szabadság</option>
                        <option value="unpaid holiday">Fizetés nélküli szabadság</option>
                        <option value="sick leave">Betegszabadság</option>
                        <option value="other">Egyéb</option>
                    </select>
                </div>
                <div>
                    <label class="pl-2" for="start_date">Kezdete</label><br>
                    <input class="rounded-lg" type="date" name="start_date" value="{{old('start_date')}}">
                </div>
                <div>
                    <label class="pl-2" for="end_date">Vége</label><br>
                    <input class="rounded-lg" type="date" name="end_date" value="{{old('end_date')}}">
                </div>
                <div>
                    <label class="pl-2" for="reason">Indoklás</label><br>
                    <p class="text-gray-500 pl-2">(Opcionális)</p>
                    <input class="rounded-lg" type="text" name="reason" placeholder="Indoklás" value="{{old('reason')}}"><br>
                </div>


            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark hover:text-white transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ URL::route('holiday_list') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>
