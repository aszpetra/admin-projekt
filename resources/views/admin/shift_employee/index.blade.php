<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Műszak beosztások') }}
        </h2>
    </x-slot>
    <div class="max-w-2xl mx-auto my-12 p-4 sm:p-6 lg:p-8 flex justify-center">
        <div class="py-7 flex justify-center">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <p> {{ \session::get('success')}} </p>
                    </div>
                @endif
                <table class="min-w-full text-left text-sm font-light border border-collapse">
                    <thead class="border-b font-medium dark:border-neutral-200">
                    <tr>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Részleg</th>
                        <th class="px-6 py-4">Létszám</th>
                        <th class="px-6 py-4">Kezdés</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shifts as $data)
                        <tr
                            class="border-b transition duration-300 ease-in-out hover:bg-zinc-200 dark:border-neutral-200 dark:hover:bg-zinc-400 hover:text-gray-100"
                        >
                            <td class="whitespace-nowrap px-6 py-4">{{$data->id}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$data->name}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$data->people}}</td>
                            <td class="whitespace-nowrap px-6 py-4">{{$data->start}}</td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('shift_employee.show', $data->id)">
                                            {{ __('Névsor') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('shift_employee.edit', $data->id)">
                                            {{ __('Beosztás szerkesztése') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('shift_log.destroy', $data->id) }}">
                                            @csrf
                                            @method('delete')
                                            <x-dropdown-link :href="route('shift_log.destroy', ['shift_log' => $data->id])" onclick="event.preventDefault(); this.closest('form').submit();">
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
