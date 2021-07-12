<x-guest-layout>
    <div class="bg-gray-100 h-screen w-screen">
        <div class="flex flex-col items-center flex-1 h-full justify-center px-4 sm:px-0">
            <div class="flex rounded-lg shadow-lg w-full sm:w-3/4 lg:w-1/2 bg-white sm:mx-0" style="height: 500px">
                <div class="flex flex-col w-full md:w-1/2 p-4">
                    <div class="flex flex-col flex-1 justify-center mb-8">
                        <div class="flex flex-col items-center flex-1 h-full justify-center">
                            <x-jet-authentication-card-logo/>
                        </div>
                        <div class="w-full mt-4">

                            <x-jet-validation-errors class="mb-4"/>

                            @if (session('status'))
                                <div class="mb-4 font-medium text-sm text-green-600">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form class="form-horizontal w-3/4 mx-auto" method="POST" action="{{ route('login') }}">
                                @csrf

                                <div>
                                    <x-jet-label for="email" value="{{ __('Email') }}"/>
                                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email"
                                                 :value="old('email')"
                                                 required autofocus autocomplete="current-email"/>
                                </div>

                                <div class="mt-4">
                                    <x-jet-label for="password" value="{{ __('Password') }}"/>
                                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password"
                                                 required
                                                 autocomplete="current-password"/>
                                </div>

                                <div class="block mt-4">
                                    <label for="remember_me" class="flex items-center">
                                        <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                    </label>
                                </div>

                                <div class="flex flex-col mt-8">
                                    <button type="submit"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </form>
                            <div class="text-center mt-4">
                                @if (Route::has('password.request'))
                                    <a class="no-underline hover:underline text-blue-dark text-xs"
                                       href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                    or
                                @endif
                                <a class="no-underline hover:underline text-blue-dark text-xs"
                                   href="{{ route('register') }}">
                                    {{ __('register') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden md:block md:w-1/2 rounded-r-lg"
                     style="background: url({{asset('images/4.jpg') }}); background-size: cover; background-position: center center;">
                    <div class="flex items-end fill-height h-full">
                        <p class="body-1 text-white px-2  mx-6 mb-12"
                           style="border-radius: 10px;background-color:rgba(10, 10, 10, 0.38);"
                        >
                            <i>¡Haciendo a la
                                Gente Exitosa en
                                un mundo
                                cambiante!.
                            </i>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-guest-layout>
