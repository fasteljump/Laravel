@if (session('success'))
  <div class="form-message is-visible is-success" style="margin-bottom: 16px;" aria-live="polite">
    {{ session('success') }}
  </div>
@endif

@if ($errors->any())
  <div class="form-message is-visible is-error" style="margin-bottom: 16px;" aria-live="polite">
    {{ $errors->first() }}
  </div>
@endif
