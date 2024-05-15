<div class="py-12">
    <div wire:loading.delay wire:target="export"
        class="z-50 flex fixed left-0 top-0 bottom-0 w-full bg-white bg-opacity-90">
        <div>
            <svg aria-hidden="true"
                class="m-auto mt-1/4 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                    fill="currentColor" />
                <path
                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                    fill="currentFill" />
            </svg>
        </div>
    </div>
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            @if (session()->has('message'))
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3"
                    role="alert">
                    <div class="flex">
                        <div>
                            <p class="text-sm">{{ session('message') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex sm:justify-between sm:flex-row flex-col">
                <div class="mt-1 mb-4 flex flex-row">
                    <x-primary-button class="bg-blue-900 hover:bg-blue-400 grow mr-2 justify-center"
                        wire:click.prevent="create()" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>

                        Nuevo
                    </x-primary-button>
                    <x-primary-button class="bg-blue-900 hover:bg-blue-400 grow justify-center"
                        wire:click.prevent="export()" wire:loading.attr="disabled">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-1">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                        </svg>

                        Exportar
                    </x-primary-button>
                </div>
                <div class="min-w-fit flex justify-between items-center">
                    <x-input-label class="align-middle inline-block pr-1">Mostrar: </x-input-label>
                    <select wire:model="pageNumber" name="pageNumber"
                        class="inline-block align-middle border-gray-300 focus:border-blue-900 focus:ring-blue-900 rounded-md shadow-sm"
                        required>
                        @foreach ($pageNumbers as $p)
                            <option value="{{ $p }}">{{ $p }}</option>
                        @endforeach
                    </select>
                    <x-input-label class="align-middle inline-block pl-1">Registros </x-input-label>
                </div>
                <div class="mt-2 mb-2 sm:text-end sm:mt-0 sm:mb-0 sm:w-auto flex flex-row justify-end items-center">
                    <x-text-input wire:model.debounce.500ms="q" type="search" placeholder="Buscar..." class="w-full" />
                    <div wire:loading wire:target="q">
                        <div>
                            <svg aria-hidden="true"
                                class="m-auto mt-1/4 w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                                viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor" />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            @if ($isModalOpen)
                @include('usuarios.create')
            @endif
            @if ($confirmDelete)
                @include('usuarios.delete')
            @endif
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

                <table class="w-full text-sm text-left text-gray-500 whitespace-nowrap">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base" wire:click="sortBy('id')">No.
                                        <x-sort-icon sortField="id" :sort-by="$sortBy" :sort-asc="$sortAsc" />

                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base" wire:click="sortBy('name')">Nombre
                                        <x-sort-icon sortField="name" :sort-by="$sortBy" :sort-asc="$sortAsc" />

                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base" wire:click="sortBy('email')">Email
                                        <x-sort-icon sortField="email" :sort-by="$sortBy" :sort-asc="$sortAsc" />

                                    </button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base" wire:click="sortBy('telefono')">Teléfono
                                        <x-sort-icon sortField="telefono" :sort-by="$sortBy" :sort-asc="$sortAsc" />

                                    </button>
                                </div>
                            </th>
                            {{-- <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base" wire:click="sortBy('cliente')">Cliente</button>
                                    <x-sort-icon sortField="cliente" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                                </div>
                            </th> --}}
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <button class="text-base"">Rol</button>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr class="bg-white border-b">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    {{ $usuario->id }}</td>
                                <td class="px-6 py-4">{{ $usuario->name }}</td>
                                <td class="px-6 py-4">{{ $usuario->email }}</td>
                                {{-- <td class="px-6 py-4">{{ $usuario->cliente ? $usuario->cliente->razon_social:"" }}</td> --}}
                                <td class="px-6 py-4">{{ $usuario->telefono }}</td>
                                <td class="px-6 py-4">
                                    {{ $usuario->getRoleNames()->count() > 0 ? $usuario->getRoleNames()->first() : '' }}
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <x-primary-button onmouseenter="addTooltip(event,'top','tooltipGeneric','Editar')"
                                        onmouseleave="removeTooltip(event,'top','tooltipGeneric','')"
                                        onblur="removeTooltip(event,'top','tooltipGeneric','')"
                                        wire:click="edit({{ $usuario->id }})"
                                        class="flex px-4 py-2 bg-blue-900 hover:bg-blue-400 cursor-pointer"
                                        wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </x-primary-button>
                                    <x-primary-button
                                        onmouseenter="addTooltip(event,'top','tooltipGeneric','Eliminar')"
                                        onmouseleave="removeTooltip(event,'top','tooltipGeneric','')"
                                        onblur="removeTooltip(event,'top','tooltipGeneric','')"
                                        wire:click="openModalDelete({{ $usuario->id }})" type="button"
                                        class="flex px-4 py-2 bg-red-800 hover:bg-red-400 cursor-pointer"
                                        wire:loading.attr="disabled">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </x-primary-button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $usuarios->links() }}
            </div>

        </div>
    </div>
</div>
