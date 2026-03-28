<x-layouts.setup :step="null" :totalSteps="null">
    <style>
        .login-card {
            animation: cardEnter 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(16px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .brand-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 50%, #ec4899 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .brand-icon {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        .login-input {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .login-input:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
            background-color: #fff;
        }
        .login-input:hover:not(:focus) {
            border-color: #c7d2fe;
        }

        .login-btn {
            background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25), 0 1px 2px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }
        .login-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #818cf8 0%, #8b5cf6 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .login-btn:hover::before {
            opacity: 1;
        }
        .login-btn:hover {
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.35), 0 2px 4px rgba(0, 0, 0, 0.05);
            transform: translateY(-1px);
        }
        .login-btn:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
        }
        .login-btn span {
            position: relative;
            z-index: 1;
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
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            flex-shrink: 0;
        }
        .custom-checkbox:hover {
            border-color: #a5b4fc;
        }
        .custom-checkbox:checked {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border-color: #6366f1;
            box-shadow: 0 1px 4px rgba(99, 102, 241, 0.3);
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
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
            border-color: #818cf8;
        }

        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            letter-spacing: 0.01em;
        }

        .form-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d5db;
            transition: color 0.2s ease;
            pointer-events: none;
        }
        .login-input:focus ~ .input-icon {
            color: #a5b4fc;
        }

        .divider-line {
            background: linear-gradient(90deg, transparent, #e5e7eb 30%, #e5e7eb 70%, transparent);
            height: 1px;
        }
    </style>

    <div class="login-card">
        {{-- Branding --}}
        <div class="mb-8 text-center">
            <div class="brand-icon mx-auto mb-5 flex h-12 w-12 items-center justify-center rounded-2xl">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h2 class="text-xl font-bold tracking-tight text-gray-900">Welcome back</h2>
            <p class="mt-2 text-sm text-gray-400">Sign in to <span class="brand-gradient font-semibold">LoggyShip</span></p>
        </div>

        <div class="divider-line mb-7"></div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="form-label mb-2 block">Email address</label>
                <div class="form-group">
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="login-input block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 placeholder-gray-300 focus:outline-none"
                        placeholder="you@example.com">
                    <div class="input-icon">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="password" class="form-label mb-2 block">Password</label>
                <div class="form-group">
                    <input type="password" name="password" id="password" required
                        class="login-input block w-full rounded-xl border border-gray-200 bg-gray-50/50 px-4 py-3 text-sm text-gray-900 placeholder-gray-300 focus:outline-none"
                        placeholder="Enter your password">
                    <div class="input-icon">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2.5 pt-0.5">
                <input type="checkbox" name="remember" id="remember" class="custom-checkbox">
                <label for="remember" class="text-sm text-gray-500 cursor-pointer select-none">Remember me</label>
            </div>

            <div class="pt-1">
                <button type="submit" class="login-btn w-full rounded-xl px-4 py-3 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <span>Sign in</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts.setup>
