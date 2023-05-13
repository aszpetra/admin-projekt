<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dolgozók') }}
        </h2>
    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!--<div class="flex flex-col space-y-6 mt-8">-->
            <div class="my-7">
                <a href="{{ route('employees.create') }}"
                   class="bg-mountain-light m-2 p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    Új hozzáadása
                </a>
                <a href="{{url('all_employees')}}"
                   class="bg-mountain-light m-2 p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    Összes dolgozó
                </a>
            </div>
            <table class="min-w-full text-left text-sm font-light border border-collapse table-auto">
                <thead class="border-b font-medium dark:border-neutral-200">
                    <tr>
                        <th class="px-3 py-4">ID</th>
                        <th class="px-3 py-4">Név</th>
                        <th class="px-3 py-4">E-mail</th>
                        <th class="px-3 py-4">Munkaviszony</th>
                        <th class="px-3 py-4">Város</th>
                        <th class="px-3 py-4">Cím</th>
                        <th class="px-3 py-4">Telefon szám</th>
                        <th class="px-3 py-4">Születési dátum</th>
                        <th class="px-3 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach($employees as $data)
                    <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-200 dark:hover:bg-neutral-300">
                        <td class="whitespace-nowrap px-3 py-4">{{$data->id}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->name}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->email}}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if($data->type == "casual")
                                Alkalmi munka
                            @else
                                Idénymunka
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->city}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->address}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->phone}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{ date('Y-m-d',strtotime($data->born_date)) }}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            <x-dropdown>
                                <x-slot name="trigger">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                        </svg>
                                    </button>
                                </x-slot>
                                <x-slot name="content">
                                    <x-dropdown-link :href="route('employees.edit', $data->id)">
                                        {{ __('Szerkeszt') }}
                                    </x-dropdown-link>
                                    <form method="POST" action="{{ route('employees.destroy', $data->id) }}">
                                        @csrf
                                        @method('delete')
                                        <x-dropdown-link :href="route('employees.destroy', $data->id)" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Töröl') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
