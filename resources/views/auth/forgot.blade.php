@extends('layouts.app')

@section('title', 'Mot de passe oublié')

@section('content')
<main class="authentification">
    <div class="card" style="width: min(520px, 100%);">
      <h1 class="card-title">Mot de passe oublié</h1>
      <div id="formMessage" class="form-message" aria-live="polite"></div>

      <form id="mot_passe" action="{{ route('login') }}" method="get" style="margin-top:16px;">
        <div class="field" style="margin:0;">
          <label for="email_reset">Email</label>
          <input id="email_reset" name="email" type="mail" autocomplete="email"  placeholder="ex: nom@mail.com">
          <p class="field-error" id="error-reset"></p>
        </div>

        <div class="form_actions">
          <button class="btn_primary" type="submit">Envoyer le lien</button>
          <a class="btn_link" href="{{ route('login') }}">Retour</a>
        </div>
      </form>
    </div>
  </main>
@endsection

@push('scripts')
<script src="{{ asset('js/mot_passe.js') }}" defer></script>
@endpush
