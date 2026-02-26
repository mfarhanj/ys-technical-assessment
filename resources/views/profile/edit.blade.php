@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Edit Profile') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                            <input
                                id="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                            >
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <input
                                id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="email"
                            >
                            <div id="email-availability" class="form-text"></div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                {{ __('New Password') }}
                                <span class="text-muted">({{ __('leave blank to keep current password') }})</span>
                            </label>
                            <input
                                id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                autocomplete="new-password"
                            >
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm New Password') }}</label>
                            <input
                                id="password-confirm"
                                type="password"
                                class="form-control"
                                name="password_confirmation"
                                autocomplete="new-password"
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Role') }}</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ ucfirst($user->role) }}"
                                disabled
                            >
                            <div class="form-text">
                                {{ __('Role cannot be changed from this page.') }}
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" id="profile-submit">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const emailInput = document.getElementById('email');
        const availabilityText = document.getElementById('email-availability');
        const submitButton = document.getElementById('profile-submit');
        const currentEmail = '{{ $user->email }}';

        if (!emailInput || !availabilityText) {
            return;
        }

        let debounceTimer = null;

        const setStatus = (message, type) => {
            availabilityText.textContent = message || '';
            availabilityText.classList.remove('text-success', 'text-danger');
            if (type === 'success') {
                availabilityText.classList.add('text-success');
            } else if (type === 'error') {
                availabilityText.classList.add('text-danger');
            }
        };

        const checkAvailability = () => {
            const email = emailInput.value.trim();

            if (!email || email === currentEmail) {
                setStatus('', null);
                if (submitButton) submitButton.disabled = false;
                return;
            }

            setStatus('Checking email availability...', null);
            if (submitButton) submitButton.disabled = true;

            const url = new URL('{{ route('profile.check-email') }}', window.location.origin);
            url.searchParams.set('email', email);

            fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json',
                },
                credentials: 'same-origin',
            })
                .then(response => response.json())
                .then(data => {
                    if (data.available) {
                        setStatus(data.message || 'Email is available.', 'success');
                        if (submitButton) submitButton.disabled = false;
                    } else {
                        setStatus(data.message || 'Email is already taken.', 'error');
                        if (submitButton) submitButton.disabled = true;
                    }
                })
                .catch(() => {
                    setStatus('Unable to check email right now.', 'error');
                    if (submitButton) submitButton.disabled = false;
                });
        };

        emailInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(checkAvailability, 500);
        });

        emailInput.addEventListener('blur', checkAvailability);
    })();
</script>
@endsection

