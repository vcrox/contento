<div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
    <div class="flex items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
            role="dialog" aria-modal="true" aria-labelledby="modal-headline">
            <form>
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <p>Desea eliminar el registro?</p>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex flex-row items-center justify-center">
                    <span class="grow rounded-md shadow-sm sm:w-auto mr-3">
                        <x-primary-button class="w-full bg-red-800 hover:bg-red-400 justify-center" wire:click.prevent="delete()" type="button" wire:loading.attr="disabled">
                            Eliminar
                        </x-primary-button>
                    </span>
                     <span class="grow sm:ml-3 rounded-md shadow-sm sm:w-auto">
                        <x-secondary-button class="w-full justify-center" wire:click="closeModalDelete()" type="button" wire:loading.attr="disabled">
                            Cerrar
                        </x-secondary-button>
                    </span>


                </div>
            </form>
        </div>
    </div>
</div>
