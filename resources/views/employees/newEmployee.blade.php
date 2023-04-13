<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dolgozók') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="name">Név</label><br>
                    <input class="rounded-lg" type="text" name="name" placeholder="Név" value="{{old('name')}}"><br>
                </div>
                <div>
                    <label class="pl-2" for="email">E-mail</label><br>
                    <input class="rounded-lg" type="text" name="email" placeholder="E-mail" value="{{old('email')}}"><br>
                </div>
                <div>
                    <label class="pl-2" for="city">Város</label><br>
                    <input class="rounded-lg" type="text" name="city" placeholder="Város" value="{{old('city')}}" ><br>
                </div>
                <div>
                    <label class="pl-2" for="address">Cím</label><br>
                    <input class="rounded-lg" type="text" name="address" placeholder="Cím" value="{{old('address')}}"><br>
                </div>
                <div>
                    <label class="pl-2" for="phone">Telefonszám</label><br>
                    <input class="rounded-lg" type="text" name="phone" placeholder="+36 20 123 4567" value="{{old('phone')}}">
                </div>
                <div>
                    <label class="pl-2" for="born_date">Születési dátum</label><br>
                    <input class="rounded-lg" type="date" name="born_date" value="{{old('born_date')}}">
                </div>
                <div>
                    <label class="pl-2" for="company_id">Cég</label><br>
                    <select name="company_id" id="company_id" class="pr-10 py-3 rounded-lg" disabled>
                        @php($comp_id = session('company_id'))
                        @php($comp_name = session('company_name'))
                        <option value="{{$comp_id}}">{{$comp_name}}</option>
                    </select>
                </div>
                <div>
                    <label class="pl-2" for="is_efo">Munkaviszony</label><br>
                    <select name="is_efo" id="is_efo" class="pr-10 py-3 rounded-lg">
                        <option value="efo">Egyszerűsített foglalkozatott</option>
                        <option value="nemEfo">Jogviszony</option>
                    </select>
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark hover:text-white transition duration-300 ease-in-out">
                    {{ __('Save') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ URL::route('employees.index') }}'">
                    {{ __('Back') }}
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>
