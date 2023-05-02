<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dolgozók') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('employees.update', [ 'employee' => $employee[0]->id ]) }}">
            @csrf
            @method('patch')
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="name">Név</label><br>
                    <input class="rounded-lg" type="text" name="name" placeholder="Név" value="{{ $employee[0]->name }}"><br>
                </div>
                <div>
                    <label class="pl-2" for="email">E-mail</label><br>
                    <input class="rounded-lg" type="text" name="email" placeholder="E-mail" value="{{ $employee[0]->email }}"><br>
                </div>
                <div>
                    <label class="pl-2" for="city">Város</label><br>
                    <input class="rounded-lg" type="text" name="city" placeholder="Város" value="{{ $employee[0]->city }}" ><br>
                </div>
                <div>
                    <label class="pl-2" for="address">Cím</label><br>
                    <input class="rounded-lg" type="text" name="address" placeholder="Cím" value="{{ $employee[0]->address }}"><br>
                </div>
                <div>
                    <label class="pl-2" for="phone">Telefonszám</label><br>
                    <input class="rounded-lg" type="text" name="phone" placeholder="+36 20 123 4567" value="{{ $employee[0]->phone }}">
                </div>
                <div>
                    <label class="pl-2" for="born_date">Születési dátum</label><br>
                    <input class="rounded-lg" type="date" name="born_date" value="{{ date('Y-m-d',strtotime($employee[0]->born_date)) }}">
                </div>
                <div>
                    <label class="pl-2" for="company_id">Cég</label><br>
                    <select name="company_id" id="company_id" class="pr-10 py-3 rounded-lg">
                        @foreach($companies as $data)
                            @if($employee[0]->company_id == $data->id)
                                <option value="{{$data->id}}" selected>{{$data->name}}</option>
                            @else
                                <option value="{{$data->id}}">{{$data->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark hover:text-white transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ URL::route('employees.index') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>

