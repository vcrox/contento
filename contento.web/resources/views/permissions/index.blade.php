@role('SuperAdministrador')
    <x-app-layout>

        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Permisos') }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <livewire:lista-permissions />
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endrole
@role('Administrador')
    <x-admin-layout>

        <x-slot name="header">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ __('Permisos') }}
            </h2>
        </x-slot>
        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <livewire:lista-permissions />
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
@endrole
