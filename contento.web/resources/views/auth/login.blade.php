<x-guest-layout>
    <div class="bg-blue-400 h-screen w-screen">
        <div class="flex flex-col items-center flex-1 h-full justify-center px-0">
            <div class="flex rounded-lg shadow-lg w-full bg-white mx-0">
                <div class="hidden md:block md:w-1/2 rounded-r-lg"
                    style="background: url('https://images.unsplash.com/photo-1576737064520-f45d313d17ff?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1888&q=80'); background-size: cover; background-position: center center;">
                </div>
                <div class="flex flex-col w-full md:w-1/2">
                    <div class="flex flex-col flex-1">
                        <div class="min-h-screen flex flex-col items-center pt-10 sm:pt-0">
                            <div class="mt-20">
                                <a href="/">
                                    <x-application-logo class="h-72 fill-current text-gray-500" />
                                </a>
                            </div>
                            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white overflow-hidden sm:rounded-lg">
                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                <form class="form-horizontal w-full mx-auto" method="POST"
                                    action="{{ route('login') }}">
                                    @csrf

                                    <!-- Email Address -->
                                    <div>
                                        <x-input-label for="email" :value="__('Email')" />
                                        <x-text-input id="email" class="block mt-1 w-full" type="email"
                                            name="email" :value="old('email')" required autofocus
                                            autocomplete="username" />
                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                    </div>

                                    <!-- Password -->
                                    <div class="mt-4">
                                        <x-input-label for="password" :value="__('Contraseña')" />

                                        <x-text-input id="password" class="block mt-1 w-full" type="password"
                                            name="password" required autocomplete="current-password" />

                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>

                                    <!-- Remember Me -->
                                    <div class="mt-4 hidden">
                                        <label for="remember_me" class="inline-flex items-center">
                                            <input id="remember_me" type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-blue-500"
                                                name="remember">
                                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                        </label>
                                    </div>

                                    <div class="flex flex-col mt-8 items-center">
                                        <x-primary-button class="bg-blue-900 hover:bg-blue-400 w-full justify-center">
                                            {{ __('Iniciar sesión') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>

</x-guest-layout>
