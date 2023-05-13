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
                <a href="{{route('employees.index')}}"
                   class="bg-mountain-light m-2 p-4 rounded-lg hover:bg-mountain-dark transition duration-300 ease-in-out">
                    Vissza
                </a>
            </div>
            <table class="min-w-full text-left text-sm font-light border border-collapse table-auto">
                <thead class="border-b font-medium dark:border-neutral-200">
                <tr>
                    <th class="px-3 py-4">ID</th>
                    <th class="px-3 py-4">Név</th>
                    <th class="px-3 py-4">Cég</th>
                    <th class="px-3 py-4">Alkalmi munka</th>
                    <th class="px-3 py-4">Idénymunka</th>
                    <th class="px-3 py-4">Aktív</th>
                    <th class="px-3 py-4"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($employees as $data)
                    <tr class="border-b transition duration-300 ease-in-out hover:bg-neutral-100 dark:border-neutral-200 dark:hover:bg-neutral-300">
                        <td class="whitespace-nowrap px-3 py-4">{{$data->id}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->name}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->company}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->casual_days}}</td>
                        <td class="whitespace-nowrap px-3 py-4">{{$data->seasonal_days}}</td>
                        <td class="whitespace-nowrap px-3 py-4">
                            @if ($data->is_active)
                                Aktív
                            @else
                                Nem aktív
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

                                    <x-dropdown-link :href="route('general_edit', ['id' => $data->id])">
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
