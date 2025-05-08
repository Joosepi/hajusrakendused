@extends('layouts.app')

@section('title', 'API Fetch')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <h2 class="card-title text-light mb-4">API Fetch</h2>
                    
                    <!-- API Input Form -->
                    <div class="mb-4">
                        <div class="input-group">
                            <input type="text" 
                                   id="apiUrl" 
                                   class="form-control bg-darker text-light" 
                                   placeholder="Enter API URL">
                            <button class="btn btn-primary" id="fetchData">
                                <i class="fas fa-search me-2"></i>Fetch Data
                            </button>
                        </div>
                    </div>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="text-center my-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>

                    <!-- Results Cards Container -->
                    <div id="resultsContainer" class="mt-4">
                        <div class="row g-4" id="dataCards">
                            <!-- Cards will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-darker {
        background-color: rgba(26, 26, 26, 0.95) !important;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .form-control {
        background-color: var(--darker-bg);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        padding: 0.75rem 1rem;
    }

    .form-control:focus {
        background-color: var(--darker-bg);
        border-color: var(--accent-color);
        color: var(--text-primary);
        box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
    }

    .data-card {
        background: #1e1e1e;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .data-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
        border-color: #3b82f6;
    }

    .data-card p {
        color: #ffffff;
        margin-bottom: 0.75rem;
        padding: 0.5rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 6px;
    }

    .data-card strong {
        color: #3b82f6;
        display: block;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Loading animation */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    .api-img-preview {
        max-width: 100%;
        max-height: 200px;
        border-radius: 8px;
        display: block;
        margin: 0.5rem 0;
        background: #222;
        object-fit: contain;
        box-shadow: 0 2px 8px rgba(0,0,0,0.3);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const apiUrl = document.getElementById('apiUrl');
    const fetchButton = document.getElementById('fetchData');
    const dataCards = document.getElementById('dataCards');
    const loadingIndicator = document.getElementById('loadingIndicator');

    fetchButton.addEventListener('click', async function() {
        // Show loading indicator
        loadingIndicator.classList.remove('d-none');
        dataCards.innerHTML = '';

        try {
            const response = await fetch(apiUrl.value);
            const data = await response.json();
            
            // Clear previous cards
            dataCards.innerHTML = '';

            // Create cards based on the response structure
            if (Array.isArray(data)) {
                data.forEach((item, index) => createCard(item, `Item ${index + 1}`));
            } else if (typeof data === 'object') {
                if (data.data && Array.isArray(data.data)) {
                    // Handle common API response format with data array
                    data.data.forEach((item, index) => createCard(item, `Item ${index + 1}`));
                } else {
                    // Single object response
                    createCard(data);
                }
            }
        } catch (error) {
            // Create error card
            const errorCard = document.createElement('div');
            errorCard.className = 'col-12';
            errorCard.innerHTML = `
                <div class="data-card text-center">
                    <p class="text-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${error.message}
                    </p>
                </div>
            `;
            dataCards.appendChild(errorCard);
        } finally {
            // Hide loading indicator
            loadingIndicator.classList.add('d-none');
        }
    });

    function createCard(data) {
        const col = document.createElement('div');
        col.className = 'col-md-4';

        const card = document.createElement('div');
        card.className = 'data-card';

        let content = '';
        for (const [key, value] of Object.entries(data)) {
            if (typeof value === 'object' && value !== null) {
                content += `<p><strong>${formatKey(key)}</strong> ${JSON.stringify(value)}</p>`;
            } else if (typeof value === 'string' && isImageUrl(value)) {
                content += `<p><strong>${formatKey(key)}</strong><br><img src="${value}" alt="${formatKey(key)}" class="api-img-preview mb-2"></p>`;
            } else {
                content += `<p><strong>${formatKey(key)}</strong> ${value}</p>`;
            }
        }

        card.innerHTML = content;
        col.appendChild(card);
        dataCards.appendChild(col);
    }

    function formatKey(key) {
        return key
            .replace(/([A-Z])/g, ' $1')
            .replace(/_/g, ' ')
            .replace(/^\w/, c => c.toUpperCase());
    }

    function isImageUrl(url) {
        return /\.(jpg|jpeg|png|gif|webp|svg)$/i.test(url);
    }
});
</script>
@endpush
@endsection 