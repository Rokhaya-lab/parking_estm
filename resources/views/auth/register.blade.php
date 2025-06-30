@extends('layouts.app')

@section('title', 'Inscription')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-green-50 to-blue-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-xl">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">🚗 Parking ESTM</h2>
            <p class="mt-2 text-sm text-gray-600">Créez votre compte</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="form-label">Prénom</label>
                    <input id="first_name" type="text" class="form-input" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="Prénom">
                    @error('first_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="last_name" class="form-label">Nom</label>
                    <input id="last_name" type="text" class="form-input" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder="Nom">
                    @error('last_name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="email" class="form-label">Adresse Email</label>
                <input id="email" type="email" class="form-input" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="votre.email@estm.sn">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="form-label">Mot de passe</label>
                <input id="password" type="password" class="form-input" name="password" required autocomplete="new-password" placeholder="••••••••">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
            </div>

            <div>
                <button type="submit" class="btn-primary w-full">
                    S'inscrire
                </button>
            </div>
        </form>
        
        <div class="text-center">
            <p class="text-sm text-gray-600">
                Déjà un compte? 
                <a href="{{ route('login') }}" class="font-medium text-parking-primary hover:text-blue-700">Se connecter</a>
            </p>
        </div>
    </div>
</div>
@endsection
