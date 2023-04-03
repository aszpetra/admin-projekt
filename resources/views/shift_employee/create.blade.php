<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszak beosztás') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('shift_employee.store', ['shift' => $shift->id]) }}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="shift">Részleg</label><br>
                    <input type="text" class="pl-3 py-3 rounded-lg" disabled value="{{$shift->name}}"/>
                    <input type="text" hidden name="shift" class="pl-3 py-3 rounded-lg" disabled value="{{$shift->id}}"/>
                </div>
                <div>
                    <label class="pl-2" for="date">Műszak kezdete</label><br>
                    <input name="date" type="datetime-local" class="pr-10 py-3 rounded-lg"/>
                </div>
                <div>
                    <label class="pl-2" for="people">Létszám</label><br>
                    <input type="number" name="people" class="pr-10 py-3 rounded-lg" placeholder="Létszám" value="{{old('people')}}"/>
                </div>
                <div>
                    <label class="pl-2" >Dolgozók</label><br>
                    @php($i=0)
                    @foreach($users as $data)
                        <input type="checkbox" name="employees[]" value="{{$data->id}}" class="px-2 py-2 m-2 rounded-lg mountain-dark-500">
                        <label>{{$data->name}}</label><br>
                    @endforeach
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ URL::route('shift_employee.index') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>

        </form>
    </div>
</x-app-layout>
