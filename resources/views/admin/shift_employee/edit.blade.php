<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszak beosztás') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('shift_employee.update', ['shift_employee' => $shift->log_id]) }}">
            @csrf
            @method('patch')
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="shift">Részleg</label><br>
                    <input type="text" class="pl-3 py-3 rounded-lg" disabled value="{{$shift->name}}"/>
                </div>
                <div>
                    <label class="pl-2" for="time">Műszak kezdete</label><br>
                    <input name="time" type="datetime-local" class="pr-10 py-3 rounded-lg" value="{{ Carbon\Carbon::create($shift->start)->toDateTimeString() }}"/>
                </div>
                <div>
                    <label class="pl-2" for="people">Létszám</label><br>
                    <input type="number" name="people" class="pr-10 py-3 rounded-lg" placeholder="Létszám" value="{{$shift->people}}"/>
                </div>
                <div>
                    <label class="pl-2" >Dolgozók</label><br>
                    @foreach($checked_employees as $data)
                        <input type="checkbox" name="original_employees[]" value="{{$data->id}}" class="px-2 py-2 m-2 rounded-lg mountain-dark-500" checked>
                        <label>{{$data->name}}</label><br>
                    @endforeach
                    @foreach($employees as $emp)
                        <input type="checkbox" name="other_employees[]" value="{{$emp->id}}" class="px-2 py-2 m-2 rounded-lg mountain-dark-500">
                        <label>{{$emp->name}}</label><br>
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
