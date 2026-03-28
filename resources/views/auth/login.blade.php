<x-layouts.setup :step="null" :totalSteps="null">
    <style>
        .login-card {
            animation: cardEnter 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .brand-gradient {
            background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .login-input {
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .login-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            background-color: #fff;
        }
        .login-btn {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 1px 3px rgba(99, 102, 241, 0.3);
        }
        .login-btn:hover {
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
            transform: translateY(-1px);
        }
        .login-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(99, 102, 241, 0.3);
        }
        .custom-checkbox {
            appearance: none;
            -webkit-appearance: none;
            width: 1.125rem;
            height: 1.125rem;
            border: 1.5px solid #d1d5db;
            border-radius: 0.3rem;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            flex-shrink: 0;
        }
        .custom-checkbox:checked {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: #6366f1;
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 5px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-checkbox:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            border-color: #6366f1;
        }
        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            letter-spacing: 0.01em;
        }
    </style>

    <div class="login-card">
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold tracking-tight text-gray-900">Welcome back</h2>
            <p class="mt-1.5 text-sm text-gray-400">Sign in to <span class="brand-gradient font-semibold">LoggyShip</span></p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="form-label mb-1.5 block">Email address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                    class="login-input block w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                    placeholder="you@example.com">
            </div>

            <div>
                <label for="password" class="form-label mb-1.5 block">Password</label>
                <input type="password" name="password" id="password" required
                    class="login-input block w-full rounded-lg border border-gray-200 bg-gray-50/50 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none"
                    placeholder="Enter your password">
            </div>

            <div class="flex items-center gap-2.5">
                <input type="checkbox" name="remember" id="remember" class="custom-checkbox">
                <label for="remember" class="text-sm text-gray-500 cursor-pointer select-none">Remember me</label>
            </div>

            <button type="submit" class="login-btn w-full rounded-lg px-4 py-2.5 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Sign in
            </button>
        </form>
    </div>
</x-layouts.setup>
