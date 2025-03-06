<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo .jpg') }}" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mx-auto max-w-screen-xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-lg text-center">
            <h1 class="text-2xl font-bold sm:text-3xl">Welcome Back!</h1>
            <p class="mt-4 text-gray-600">
                Sign in to your account to continue managing your finances
            </p>
        </div>

        <form class="mx-auto mb-0 mt-8 max-w-md space-y-4" method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="sr-only" for="email">Email</label>
                <div class="relative">
                    <input
                        placeholder="Enter your email"
                        class="w-full rounded-lg border-gray-300 p-4 pe-12 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        id="email"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <span class="absolute inset-y-0 end-0 grid place-content-center px-4">
                        <svg
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            fill="none"
                            class="h-6 w-6 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                stroke-width="2"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                            ></path>
                        </svg>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label class="sr-only" for="password">Password</label>
                <div class="relative">
                    <input
                        placeholder="Enter your password"
                        class="w-full rounded-lg border-gray-300 p-4 pe-12 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent"
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    />
                    <span class="absolute inset-y-0 end-0 grid place-content-center px-4">
                        <svg
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            fill="none"
                            class="h-6 w-6 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                stroke-width="2"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                            ></path>
                            <path
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                stroke-width="2"
                                stroke-linejoin="round"
                                stroke-linecap="round"
                            ></path>
                        </svg>
                    </span>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me section replacement -->
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    No account yet?
                    <a href="{{ route('register') }}" class="underline hover:text-amber-600">Create one</a>
                </p>

                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <button
                class="w-full rounded-lg bg-amber-500 px-5 py-3 text-sm font-medium text-white hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-opacity-50"
                type="submit"
            >
                {{ __('Sign In') }}
            </button>
        </form>
    </div>
</body>
</html>