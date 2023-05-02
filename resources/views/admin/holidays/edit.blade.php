<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hiányzások') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <form method="POST" action="{{ route('holidays.update', [ 'holiday' => $holiday[0]->hol_id ]) }}">
            @csrf
            @method('patch')
            <div class="flex flex-col space-y-6">
                <div>
                    <label class="pl-2" for="type">Hiányzás típusa</label><br>
                    <select name="type" id="type" class="pr-10 py-3 rounded-lg">
                        @if($holiday[0]->type == "holiday")
                            <option value="holiday" selected>Fizetett szabadság</option>
                        @else
                            <option value="holiday">Fizetett szabadság</option>
                        @endif
                        @if($holiday[0]->type == 'unpaid holiday')
                            <option value="unpaid holiday" selected>Fizetés nélküli szabadság</option>
                        @else
                            <option value="unpaid holiday">Fizetés nélküli szabadság</option>
                        @endif
                        @if($holiday[0]->type == 'sick leave')
                            <option value="sick leave" selected>Betegszabadság</option>
                        @else
                            <option value="sick leave">Betegszabadság</option>
                        @endif
                        @if($holiday[0]->type == 'other')
                            <option value="other" selected>Egyéb</option>
                        @else
                            <option value="other">Egyéb</option>
                        @endif
                    </select>
                </div>
                <div>
                    <label class="pl-2" for="employee_id">Dolgozó</label><br>
                    <select name="employee_id" id="employee_id" class="pr-10 py-3 rounded-lg">
                        @foreach($employees as $emp)
                            @if($emp->id == $holiday[0]->employee_id)
                                <option value="{{$emp->id}}" selected>{{$emp->name}}</option>
                            @else
                                <option value="{{$emp->id}}">{{$emp->name}}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="pl-2" for="start_date">Kezdete</label><br>
                    <input class="rounded-lg" type="date" name="start_date" value="{{ date('Y-m-d',strtotime($holiday[0]->start_date)) }}">
                </div>
                <div>
                    <label class="pl-2" for="end_date">Vége</label><br>
                    <input class="rounded-lg" type="date" name="end_date" value="{{ date('Y-m-d',strtotime($holiday[0]->end_date)) }}">
                </div>
                <div>
                    <label class="pl-2" for="reason">Indoklás (opcionális)</label><br>
                    <input class="rounded-lg" type="text" name="reason" placeholder="Indoklás" value="{{$holiday[0]->reason}}"><br>
                </div>
                <div>
                    @if($holiday[0]->approved)
                        <input type="radio" id="is_approved" name="approved" value="yes" checked>
                        <label for="is_approved">Jóváhagyott</label><br>
                        <input type="radio" id="not_approved" name="approved" value="no">
                        <label for="not_approved">Kérvényezett</label><br>
                    @else
                        <input type="radio" id="is_approved" name="approved" value="yes">
                        <label for="is_approved">Jóváhagyott</label><br>
                        <input type="radio" id="not_approved" name="approved" value="no" checked>
                        <label for="not_approved">Kérvényezett</label><br>
                    @endif
                </div>
            </div>
            <div>
                <x-primary-button class="mt-4 bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark hover:text-white transition duration-300 ease-in-out">
                    {{ __('Mentés') }}
                </x-primary-button>
                <x-secondary-button class="mt-4 bg-white p-4 rounded-lg hover:bg-mountain-light hover:text-white transition duration-300 ease-in-out"
                                    onClick="window.location='{{ URL::route('holidays.index') }}'">
                    {{ __('Vissza') }}
                </x-secondary-button>
            </div>
        </form>
    </div>
</x-app-layout>
