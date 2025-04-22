<x-app-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Profile Overview Card -->
                <div class="card bg-dark text-white mb-4 border-0">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="profile-avatar me-4">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <span class="fs-1">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            </div>
                            <div>
                                <h2 class="mb-1">{{ Auth::user()->name }}</h2>
                                <p class="text-muted mb-0">{{ Auth::user()->email }}</p>
                                @if(Auth::user()->is_admin)
                                    <span class="badge bg-primary mt-2">Admin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Information -->
                <div class="card bg-dark text-white mb-4 border-0">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h3 class="h5 mb-0">Profile Information</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('patch')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control bg-darker" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control bg-darker" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>

                <!-- Update Password -->
                <div class="card bg-dark text-white mb-4 border-0">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h3 class="h5 mb-0">Update Password</h3>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control bg-darker" id="current_password" name="current_password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control bg-darker" id="password" name="password" required>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control bg-darker" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>

                <!-- Delete Account -->
                <div class="card bg-dark text-white border-0">
                    <div class="card-header bg-dark border-bottom border-secondary">
                        <h3 class="h5 mb-0 text-danger">Delete Account</h3>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                    <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                        @csrf
                        @method('delete')
                        <div class="mb-3">
                            <label for="delete_password" class="form-label">Password</label>
                            <input type="password" class="form-control bg-darker" id="delete_password" name="password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="delete-account-form" class="btn btn-danger">Delete Account</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-darker {
            background-color: #1a1a1a !important;
            border-color: #2d2d2d;
            color: #fff;
        }
        .bg-darker:focus {
            background-color: #1a1a1a;
            border-color: #3b82f6;
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }
        .card {
            background-color: #1e1e1e;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            color: #e5e5e5;
        }
        .text-muted {
            color: #9ca3af !important;
        }
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .btn-danger {
            background-color: #dc2626;
            border-color: #dc2626;
        }
        .btn-danger:hover {
            background-color: #b91c1c;
            border-color: #b91c1c;
        }
        .modal-content {
            border: 1px solid #2d2d2d;
        }
    </style>
</x-app-layout>
