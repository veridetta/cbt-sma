@extends('template')
@section('title', 'Login BasecampTO ')
 
@section('intro-header')
@endsection

@section('main')
<div class="col-12 bg-gray-100" style="padding-top:15px;padding-bottom:12px;">
    <x-guest-layout>
        <x-jet-authentication-card>
            <x-slot name="logo">
                <img src="{{ asset('images/logo.png') }}" width="150px"/>
            </x-slot>

            <x-jet-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div>
                    <x-jet-label for="name" value="{{ __('Nama Lengkap') }}" />
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                </div>
                <div class="mt-4">
                    <x-jet-label for="sekolah" value="{{ __('Sekolah') }}" />
                    <x-jet-input id="sekolah" class="block mt-1 w-full" type="text" name="sekolah" :value="old('sekolah')" required autofocus autocomplete="sekolah" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="hp" value="{{ __('Nomor HP') }}" />
                    <x-jet-input id="hp" class="block mt-1 w-full" type="text" name="hp" :value="old('hp')" required autofocus autocomplete="phone_number" />
                </div>
                <div class="mt-4">
                    <x-jet-label for="friend_ref" value="{{ __('Refferal Teman (jika ada)') }}" />
                    <x-jet-input id="friend_ref" class="block mt-1 w-full" type="text" name="friend_ref" :value="old('friend_ref')" autocomplete="friend_ref" />
                </div>
                <div class="mt-4">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-jet-button class="ml-4">
                        {{ __('Register') }}
                    </x-jet-button>
                </div>
            </form>
        </x-jet-authentication-card>
    </x-guest-layout>
</div>
@endsection