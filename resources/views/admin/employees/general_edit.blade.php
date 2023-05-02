<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dolgozók') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{route('general_update', ['id' => $employee[0]->id])}}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="name">Név</label><br>
                    <input class="rounded-lg" type="text" name="name" placeholder="Név" value="{{ $employee[0]->name }}" disabled><br>
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
                <div>
                    @if($employee[0]->is_active)
                        <input type="radio" id="active" name="is_active" value="yes" checked>
                        <label for="active">Aktív</label><br>
                        <input type="radio" id="not_active" name="is_active" value="no">
                        <label for="not_active">Nem aktív</label><br>
                    @else
                        <input type="radio" id="active" name="is_active" value="yes">
                        <label for="active">Aktív</label><br>
                        <input type="radio" id="not_active" name="is_active" value="no"  checked>
                        <label for="not_active">Nem aktív</label><br>
                    @endif
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark hover:text-white transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ url('all_employees') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>

