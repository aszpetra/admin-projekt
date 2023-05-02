<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszak beosztás') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('shift_employee.store', ['shift' => $shift_log->id]) }}">
            @csrf
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2">Dolgozók</label><br>
                    @foreach($users as $data)
                        @if($loop->index < $shift_log->people)
                            <input type="checkbox" name="employees[]" value="{{$data->id}}" checked class="px-2 py-2 m-2 rounded-lg mountain-dark-500">
                        @else
                            <input type="checkbox" name="employees[]" value="{{$data->id}}" class="px-2 py-2 m-2 rounded-lg mountain-dark-500">
                        @endif
                        <label>{{$data->name}}</label><br>
                    @endforeach
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
