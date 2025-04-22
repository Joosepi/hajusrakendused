@extends('layouts.app')

@section('title', 'Maps')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Map Title Section -->
            <div class="map-header mb-4">
                <h2 class="text-white mb-3">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Interactive Map
                </h2>
            </div>

            <!-- Map Container -->
            <div class="maps-container position-relative">
                <div class="map-card">
                    <div id="map" style="height: 600px;"></div>
                    
                    <!-- Floating Controls -->
                    <div class="map-controls">
                        <button class="control-btn" onclick="toggleMarkersList()" title="Toggle Markers List">
                            <i class="fas fa-layer-group"></i>
                        </button>
                    </div>

                    <!-- Markers Sidebar -->
                    <div id="markersList" class="markers-sidebar">
                        <div class="sidebar-header">
                            <h3><i class="fas fa-map-marker-alt me-2"></i>Markers</h3>
                            <button class="close-sidebar" onclick="toggleMarkersList()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="markers-content">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($markers as $marker)
                                        <tr>
                                            <td>{{ $marker->name }}</td>
                                            <td class="actions">
                                                <button class="action-btn edit" onclick="editMarker({{ $marker }})" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="action-btn delete" onclick="deleteMarker({{ $marker->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Marker Modal -->
<div class="modal fade" id="markerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <span id="modalTitle">Add Marker</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="markerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label text-white">Name</label>
                        <input type="text" class="form-control bg-black text-white" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label text-white">Description</label>
                        <textarea class="form-control bg-black text-white" id="description" rows="3"></textarea>
                    </div>
                    <div class="coordinates-info">
                        <div class="mb-3">
                            <label class="form-label text-white">Latitude</label>
                            <input type="text" id="latitude" class="form-control bg-black text-white" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Longitude</label>
                            <input type="text" id="longitude" class="form-control bg-black text-white" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveMarker">Save Marker</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://js.radar.com/v4.5.1/radar.css" rel="stylesheet">
<style>
/* Map Container */
.maps-container {
    margin-bottom: 2rem;
}

.map-card {
    background: #1a1a1a;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

#map {
    width: 100%;
    height: 600px !important;
    background: #242424;
}

.maplibregl-canvas {
    border-radius: 12px;
}

.marker {
    cursor: pointer;
}

/* Map Header */
.map-header h2 {
    font-size: 1.75rem;
    font-weight: 600;
}

.map-header p {
    font-size: 1.1rem;
}

/* Map Controls */
.map-controls {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    gap: 10px;
}

.control-btn {
    background: rgba(26, 26, 26, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
}

.control-btn:hover {
    background: rgba(38, 38, 38, 0.9);
    transform: translateY(-1px);
}

/* Markers Sidebar */
.markers-sidebar {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width: 320px;
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(10px);
    z-index: 1001;
    transform: translateX(100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
}

.markers-sidebar.active {
    transform: translateX(0);
}

.sidebar-header {
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.sidebar-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #fff;
}

.close-sidebar {
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
    padding: 5px;
}

.markers-content {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

/* Table Styling */
.table {
    color: #fff;
    margin: 0;
}

.table th {
    color: #a0a0a0;
    font-weight: 500;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 12px 8px;
}

.table td {
    padding: 12px 8px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    vertical-align: middle;
}

.actions {
    display: flex;
    gap: 8px;
}

.action-btn {
    background: none;
    border: none;
    padding: 6px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.action-btn.edit {
    color: #3b82f6;
}

.action-btn.delete {
    color: #ef4444;
}

.action-btn:hover {
    transform: translateY(-1px);
}

/* Instructions Card */
.card {
    background: #1a1a1a;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
}

.text-primary {
    color: #3b82f6 !important;
}

/* Marker Label */
.marker-label {
    background: rgba(26, 26, 26, 0.9);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    backdrop-filter: blur(4px);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.15);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .main-map {
        height: 400px;
    }
    
    .markers-sidebar {
        width: 280px;
    }
}

/* Modal Dark Theme */
.modal-content {
    background-color: #121212 !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.modal-title {
    color: #ffffff;
}

.form-control {
    background-color: #000000 !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #ffffff !important;
}

.form-control:focus {
    background-color: #000000 !important;
    border-color: #3b82f6;
    color: #ffffff !important;
    box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
}

.form-label {
    color: #ffffff !important;
}

.btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
}

.coordinates-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.btn-primary {
    background-color: #3b82f6;
    border: none;
}

.btn-primary:hover {
    background-color: #2563eb;
}

.btn-secondary {
    background-color: #374151;
    border: none;
}

.btn-secondary:hover {
    background-color: #4b5563;
}
</style>
@endpush

@push('scripts')
<script src="https://js.radar.com/v4.5.1/radar.min.js"></script>
<script>
let map;
let currentMarker = null;
let markers = new Map();

async function initMap() {
    try {
        // Initialize Radar with your publishable key
        Radar.initialize('prj_live_pk_7a4256665d886dc2350f500aa79e470a68e76735');

        // Create the map using Radar's UI
        map = new Radar.ui.maplibregl.Map({
            container: 'map',
            style: 'https://api.radar.io/maps/styles/radar-dark-v1?publishableKey=prj_live_pk_7a4256665d886dc2350f500aa79e470a68e76735',
            center: [24.7536, 59.4370], // Tallinn coordinates
            zoom: 13
        });

        // Add navigation controls
        const nav = new Radar.ui.maplibregl.NavigationControl();
        map.addControl(nav, 'top-right');

        // Wait for map to load
        map.on('load', () => {
            console.log('Map loaded successfully');
            // Load markers after map is loaded
            loadMarkers();
        });

        // Add click handler for adding markers
        map.on('click', function(event) {
            const coordinates = {
                lat: event.lngLat.lat,
                lng: event.lngLat.lng
            };
            showAddMarkerModal(coordinates);
        });

    } catch (error) {
        console.error('Error initializing map:', error);
    }
}

// Load markers from the database
async function loadMarkers() {
    try {
        const response = await fetch('/markers');
        const data = await response.json();
        
        data.forEach(marker => {
            addMarkerToMap(marker);
        });
    } catch (error) {
        console.error('Error loading markers:', error);
    }
}

// Add a marker to the map
function addMarkerToMap(markerData) {
    try {
        // Create marker element
        const el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundColor = '#3b82f6';
        el.style.width = '24px';
        el.style.height = '24px';
        el.style.borderRadius = '50%';
        el.style.border = '2px solid white';

        // Create the marker
        const marker = new Radar.ui.maplibregl.Marker(el)
            .setLngLat([parseFloat(markerData.longitude), parseFloat(markerData.latitude)])
            .addTo(map);

        // Add popup
        const popup = new Radar.ui.maplibregl.Popup({ offset: 25 })
            .setHTML(`
                <div class="marker-label">
                    <strong>${markerData.name}</strong>
                    <p>${markerData.description || ''}</p>
                </div>
            `);

        marker.setPopup(popup);
        markers.set(markerData.id, marker);
    } catch (error) {
        console.error('Error adding marker:', error, markerData);
    }
}

// Show add marker modal
function showAddMarkerModal(coordinates) {
    const modal = new bootstrap.Modal(document.getElementById('markerModal'));
    document.getElementById('latitude').value = coordinates.lat;
    document.getElementById('longitude').value = coordinates.lng;
    modal.show();
}

// Toggle markers list sidebar
function toggleMarkersList() {
    const sidebar = document.getElementById('markersList');
    sidebar.classList.toggle('active');
}

// Initialize map when the page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing map...');
    initMap().catch(error => {
        console.error('Error in map initialization:', error);
    });
});

// Save marker
document.getElementById('saveMarker').addEventListener('click', async function() {
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const latitude = document.getElementById('latitude').value;
    const longitude = document.getElementById('longitude').value;

    try {
        const response = await fetch('/markers', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name,
                description,
                latitude,
                longitude
            })
        });

        const data = await response.json();
        
        if (data.success) {
            addMarkerToMap(data.marker);
            bootstrap.Modal.getInstance(document.getElementById('markerModal')).hide();
            document.getElementById('markerForm').reset();
        }
    } catch (error) {
        console.error('Error saving marker:', error);
    }
});

function editMarker(marker) {
    $('#modalTitle').text('Edit Marker');
    $('#markerId').val(marker.id);
    $('#name').val(marker.name);
    $('#description').val(marker.description);
    $('#latitude').val(marker.latitude);
    $('#longitude').val(marker.longitude);
    $('#markerModal').modal('show');
}

function deleteMarker(id) {
    if (confirm('Are you sure you want to delete this marker?')) {
        $.ajax({
            url: `/markers/${id}`,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function() {
                // Remove marker from map
                if (markers.has(id)) {
                    markers.get(id).remove();
                }
                location.reload(); // Refresh to update the list
            },
            error: function(xhr) {
                alert('Error deleting marker');
                console.error(xhr);
            }
        });
    }
}
</script>
@endpush
@endsection 