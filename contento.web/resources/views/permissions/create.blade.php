<div x-data="{ isEdit: @entangle('isEdit') }" class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="">
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1" for="txtDescripcion">
                                Descripción
                            </label>
                            <x-text-input type="text" class="w-full uppercase" id="txtDescripcion"
                                name="txtDescripcion" placeholder="Descripción..." wire:model.defer="name" />
                            @error('descripcion')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            @if ($roles->count())
                                <div class="space-y-2">
                                    <label class="block text-base font-medium text-gray-700" for="roles">Roles de
                                        usuario</label>
                                    <div class="space-x-2">
                                        @foreach ($roles as $id => $name)
                                            <div class="inline-flex space-x-1">
                                                <input class="rounded-md border-gray-300 shadow-sm" type="checkbox"
                                                    wire:model="rolesSelected" value="{{ $id }}" />
                                                <label class="text-sm font-medium text-gray-700"
                                                    for="role-{{ $id }}">{{ $name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('roles')
                                        <span class="text-sm text-red-600">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row items-center justify-center">
                    <span class="grow rounded-md shadow-sm sm:w-auto mr-3">
                        <x-primary-button class="w-full bg-blue-900 hover:bg-blue-400 justify-center"
                            wire:click.prevent="store()" type="button" wire:loading.attr="disabled">
                            Guardar
                        </x-primary-button>
                    </span>
                    <span class="grow sm:ml-3 rounded-md shadow-sm sm:w-auto">
                        <x-primary-button class="w-full bg-red-800 hover:bg-red-400 justify-center"
                            wire:click="closeModalPopover()" type="button" wire:loading.attr="disabled">
                            Cerrar
                        </x-primary-button>
                    </span>


                </div>
            </form>
        </div>
    </div>
</div>
