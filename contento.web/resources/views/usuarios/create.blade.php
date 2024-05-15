<div x-data="{ isEdit: @entangle('isEdit') }" class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtName">
                                Nombre
                            </label>
                            <x-text-input type="text" class="w-full"
                                id="txtName" name="txtName" placeholder="Nombre..." wire:model.defer="nombre" />
                            @error('nombre')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtEmail">
                                Email
                            </label>
                            <x-text-input type="text" class="w-full"
                                id="txtEmail" name="txtEmail" placeholder="Email..." wire:model.defer="email" />
                            @error('email')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtTelefono">
                                Teléfono
                            </label>
                            <x-text-input type="text" class="w-full"
                                id="txtTelefono" name="txtTelefono" placeholder="Teléfono..." wire:model.defer="telefono" />
                            @error('telefono')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div :class="isEdit ? 'hidden': ''" class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtPassword">
                                Password
                            </label>
                            <x-text-input x-bind:disabled="isEdit" type="password" class="w-full disabled:bg-gray-100"
                                id="txtPassword" name="txtPassword" placeholder="Password..." wire:model.defer="password" />
                            @error('password')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div :class="isEdit ? 'hidden': ''" class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtPasswordRepeat">
                                Repetir password
                            </label>
                            <x-text-input x-bind:disabled="isEdit" type="password" class="w-full disabled:bg-gray-100"
                                id="txtPasswordRepeat" name="txtPasswordRepeat" placeholder="Repetir Password..." wire:model.defer="repetir_password" />
                            @error('repetir_password')
                            <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtRol">
                                Rol
                            </label>
                            <select
                                class="border-gray-300 focus:border-blue-900 focus:ring-blue-900 rounded-md shadow-sm w-full"
                                wire:model.defer="rol" name="txtRol" id="txtRol">
                                <option value="">Seleccionar...</option>
                                @foreach ($roles as $rol)
                                    <option value="{{ $rol }}">{{ $rol }}</option>
                                @endforeach
                            </select>
                            @error('rol')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4 hidden">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtCliente">
                                Cliente
                            </label>
                            <select
                                class="border-gray-300 focus:border-blue-900 focus:ring-blue-900 rounded-md shadow-sm w-full"
                                wire:model.defer="cliente" name="txtCliente" id="txtCliente">
                                <option value="">Seleccionar...</option>
                                @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">{{ $cliente->razon_social }}</option>
                                @endforeach
                            </select>
                            @error('cliente')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row items-center justify-center">
                    <span class="grow rounded-md shadow-sm sm:w-auto mr-3">
                        <x-primary-button class="w-full bg-blue-900 hover:bg-blue-400 justify-center" wire:click.prevent="store()" type="button" wire:loading.attr="disabled">
                            Guardar
                        </x-primary-button>
                    </span>
                    <span class="grow sm:ml-3 rounded-md shadow-sm sm:w-auto">
                        <x-primary-button class="w-full bg-red-800 hover:bg-red-400 justify-center" wire:click="closeModalPopover()" type="button" wire:loading.attr="disabled">
                            Cerrar
                        </x-primary-button>
                    </span>


                </div>
            </form>
        </div>
    </div>
</div>
