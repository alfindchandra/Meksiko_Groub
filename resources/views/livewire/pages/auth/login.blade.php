<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;
    public bool $showPassword = false;

    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function mount()
    {
        if (old('form.email')) {
            $this->form->email = old('form.email');
        }
        if (old('form.remember')) {
            $this->form->remember = old('form.remember');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login() 
    {
        $this->validate();

        try {
            $this->form->authenticate();
            
            Session::regenerate();

            return redirect()->intended(route('dashboard', absolute: false));
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->form->reset('password');
            
            // Ambil pesan utama dari exception untuk ditampilkan sebagai flash message
            $errorMessage = collect($e->errors())->flatten()->first();
            Session::flash('error', $errorMessage);

            // Kita lemparkan kembali exception-nya agar Livewire memunculkan error pada field
            throw $e;
        }
    }
}; ?>


    <div class="w-full max-w-md bg-white p-8 shadow-[0_20px_50px_rgba(0,0,0,0.04)] rounded-[2rem] border border-gray-100">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Meksiko Group</h2>
            <p class="text-sm text-gray-400 mt-2 font-medium">Masuk untuk mengelola sistem</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        @if (session('error'))
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit="login" class="space-y-5">
            <div>
                <label for="email" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2 ml-1">Email</label>
                <input 
                    wire:model="form.email" 
                    id="email" 
                    type="email" 
                    class="w-full px-5 py-4 rounded-2xl border-none bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                    placeholder="nama@meksiko.com"
                    autofocus
                />
                <x-input-error :messages="$errors->get('form.email')" class="mt-2 ml-1" />
            </div>

            <div>
                <label for="password" class="block text-xs font-bold uppercase tracking-widest text-gray-500 mb-2 ml-1">Kata Sandi</label>
                <div class="relative">
                    <input 
                        wire:model="form.password" 
                        id="password" 
                        type="{{ $showPassword ? 'text' : 'password' }}" 
                        class="w-full px-5 py-4 rounded-2xl border-none bg-gray-50 focus:ring-2 focus:ring-indigo-500 transition duration-200"
                        placeholder="••••••••"
                    />
                    
                    <button 
                        type="button" 
                        wire:click="togglePassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-indigo-600 focus:outline-none"
                    >
                        @if($showPassword)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 014.132-5.411m0 0L21 21" />
                            </svg>
                        @endif
                    </button>
                </div>
                <x-input-error :messages="$errors->get('form.password')" class="mt-2 ml-1" />
            </div>

            <div class="flex items-center ml-1">
                <input wire:model="form.remember" id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4">
                <label for="remember" class="ml-2 text-sm text-gray-500 font-medium cursor-pointer">Ingat saya</label>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 transition duration-200 active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </div>
        </form>

        <div class="mt-12 text-center border-t border-gray-50 pt-6">
            <p class="text-[10px] uppercase tracking-[0.2em] text-gray-300 font-bold">
                &copy; {{ date('Y') }} Meksiko Group Indonesia
            </p>
        </div>
    </div>
