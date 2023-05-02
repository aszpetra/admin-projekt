<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Hiányzások') }}
        </h2>
    </x-slot>

    <div class="py-7">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="my-7">
                <a href="{{ route('holidays.create') }}"
                   class="bg-mountain-light p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    Új hozzáadása
                </a>
            </div>
            <div>
                <h2 class="p-4 font-semibold text-lg">
                    Kérelmek
                </h2>
            </div>
            <table class="min-w-full mb-16 text-left text-sm font-light border border-collapse table-auto">
                <thead class="border-b font-medium dark:border-neutral-200">
                <tr>
                    <th class="px-3 py-4">ID</th>
                    <th class="px-3 py-4">Típus</th>
                    <th class="px-3 py-4">Dolgozó</th>
                    <th class="px-3 py-4">Kezdete</th>
                    <th class="px-3 py-4">Vége</th>
                    <th class="px-3 py-4">Indoklás</th>
                    <th class="px-3 py-4">Állapot</th>
                    <th class="px-3 py-4"></th>
                </tr>
                </thead>
                <tbody>
                        @foreach($holidays as $data)
                            @if(!$data->approved)
                                <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-200 dark:hover:bg-neutral-300">
                                    <td class="whitespace-nowrap px-3 py-4">{{$data->id}}</td>
                                    @switch($data->type)
                                    @case('sick leave')
                                        <td class="whitespace-nowrap px-3 py-4">Betegszabadság</td>
                                        @break
                                    @case('holiday')
                                        <td class="whitespace-nowrap px-3 py-4">Fizetett szabadság</td>
                                        @break
                                    @case('unpaid holiday')
                                        <td class="whitespace-nowrap px-3 py-4">Fizetés nélküli szabadság</td>
                                        @break
                                    @case('other')
                                        <td class="whitespace-nowrap px-3 py-4">Egyéb</td>
                                        @break
                                    @default
                                        <td class="whitespace-nowrap px-3 py-4">Egyéb</td>
                                @endswitch
                                <td class="whitespace-nowrap px-3 py-4">{{$data->employee}}</td>
                                <td class="whitespace-nowrap px-3 py-4">{{ date('Y-m-d',strtotime($data->start_date)) }}</td>
                                <td class="whitespace-nowrap px-3 py-4">{{ date('Y-m-d',strtotime($data->end_date)) }}</td>
                                <td class="whitespace-nowrap px-3 py-4">{{$data->reason}}</td>
                                <td class="whitespace-nowrap px-3 py-4">
                                    @if($data->approved)
                                        Jóváhagyott
                                    @else
                                        Kérvényezve
                                    @endif
                                </td>
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

                                            <x-dropdown-link :href="route('holidays.edit', $data->hol_id)">
                                                {{ __('Szerkeszt') }}
                                            </x-dropdown-link>
                                            <form method="POST" action="{{ route('holidays.destroy', $data->hol_id) }}">
                                                @csrf
                                                @method('delete')
                                                <x-dropdown-link :href="route('holidays.destroy', $data->id)" onclick="event.preventDefault(); this.closest('form').submit();">
                                                    {{ __('Töröl') }}
                                                </x-dropdown-link>
                                            </form>
                                        </x-slot>
                                    </x-dropdown>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <div>
                <h2 class="p-4 font-semibold text-lg">
                    Elfogadott hiányzások
                </h2>
            </div>
            <table class="min-w-full text-left text-sm font-light border border-collapse table-auto">
                <thead class="border-b font-medium dark:border-neutral-200">
                <tr>
                    <th class="px-3 py-4">ID</th>
                    <th class="px-3 py-4">Típus</th>
                    <th class="px-3 py-4">Dolgozó</th>
                    <th class="px-3 py-4">Kezdete</th>
                    <th class="px-3 py-4">Vége</th>
                    <th class="px-3 py-4">Indoklás</th>
                    <th class="px-3 py-4">Állapot</th>
                    <th class="px-3 py-4"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($holidays as $data)
                    @if($data->approved)
                        <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-200 dark:hover:bg-neutral-300">
                        <td class="whitespace-nowrap px-3 py-4">{{$data->hol_id}}</td>
                        @switch($data->type)
                            @case('sick leave')
                            <td class="whitespace-nowrap px-3 py-4">Betegszabadság</td>
                            @break
                            @case('holiday')
                            <td class="whitespace-nowrap px-3 py-4">Fizetett szabadság</td>
                            @break
                            @case('unpaid holiday')
                            <td class="whitespace-nowrap px-3 py-4">Fizetés nélküli szabadság</td>
                            @break
                            @case('other')
                            <td class="whitespace-nowrap px-3 py-4">Egyéb</td>
                            @break
                            @default
                            <td class="whitespace-nowrap px-3 py-4">Egyéb</td>
                        @endswitch
                    <td class="whitespace-nowrap px-3 py-4">{{$data->employee}}</td>
                    <td class="whitespace-nowrap px-3 py-4">{{ date('Y-m-d',strtotime($data->start_date)) }}</td>
                    <td class="whitespace-nowrap px-3 py-4">{{ date('Y-m-d',strtotime($data->end_date)) }}</td>
                    <td class="whitespace-nowrap px-3 py-4">{{$data->reason}}</td>
                    <td class="whitespace-nowrap px-3 py-4">
                        @if($data->approved)
                            Jóváhagyott
                        @else
                            Kérvényezve
                        @endif
                    </td>
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

                                <x-dropdown-link :href="route('holidays.edit', $data->hol_id)">
                                    {{ __('Szerkeszt') }}
                                </x-dropdown-link>
                                <form method="POST" action="{{ route('holidays.destroy', $data->hol_id) }}">
                                    @csrf
                                    @method('delete')
                                    <x-dropdown-link :href="route('holidays.destroy', $data->id)" onclick="event.preventDefault(); this.closest('form').submit();">
                                        {{ __('Töröl') }}
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </td>
                </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
