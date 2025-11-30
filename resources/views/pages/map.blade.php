@extends('components.appbar')

@section('title', 'Peta Pekerjaan')

@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Peta Sebaran Pekerjaan</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Map styling */
        #map {
            height: calc(100vh - 80px);
            width: 100%;
            border-radius: 10px;
            z-index: 1;
        }

        /* Floating Filter Bar Styling */
        .filter-container {
            position: absolute;
            top: 90px;
            left: 50%;
            transform: translateX(-50%);
            width: 90%;
            max-width: 1000px;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 15px 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: space-between;

            opacity: 1;
            visibility: visible;
            transition: all 0.3s ease-in-out;
        }

        .filter-hidden {
            opacity: 0;
            visibility: hidden;
            top: 70px;
        }

        /* Toggle Button */
        .toggle-filter-btn {
            position: absolute;
            top: 90px;
            right: 20px;
            z-index: 1001;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #fff;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s, background-color 0.2s;
            color: #333;
        }

        .toggle-filter-btn:hover {
            transform: scale(1.05);
            background-color: #f8f9fa;
        }

        .toggle-filter-btn.active {
            background-color: #0d6efd;
            color: white;
        }

        /* Form Elements */
        .filter-group {
            flex: 1;
        }

        .form-select {
            border-radius: 20px;
            border: 1px solid #ddd;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
            border-color: #86b7fe;
        }

        .btn-reset {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #666;
        }

        .btn-reset:hover {
            background-color: #ffe6e6;
            color: #dc3545;
            border-color: #dc3545;
        }

        /* Legend Styling */
        .map-legend {
            position: absolute;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        
        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }
        
        .dot {
            height: 12px;
            width: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            border: 1px solid #fff;
            box-shadow: 0 0 2px #000;
        }

        @media (max-width: 768px) {
            .filter-container {
                flex-direction: column;
                padding: 15px;
                top: 150px;
                width: 95%;
            }
            .filter-group {
                width: 100%;
            }
            .toggle-filter-btn {
                top: 85px;
                right: 15px;
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>

<body data-recaps="{{ json_encode($recaps) }}">

    <button class="toggle-filter-btn active" id="toggleFilterBtn" title="Tampilkan/Sembunyikan Filter">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-funnel" viewBox="0 0 16 16">
            <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2zm1 .5v1.308l4.372 4.858A.5.5 0 0 1 7 8.5v5.306l2-.666V8.5a.5.5 0 0 1 .128-.334L13.5 3.308V2h-11z"/>
        </svg>
    </button>

    <div class="filter-container" id="filterContainer">
        <form action="{{ route('map') }}" method="GET" class="d-flex w-100 gap-2 flex-wrap align-items-center">
            
            <div class="filter-group">
                <select name="company_id" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">-- Semua PT --</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <select name="pole_size" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Ukuran --</option>
                    <option value="9 meter" {{ request('pole_size') == '9 meter' ? 'selected' : '' }}>9 Meter</option>
                    <option value="12 meter" {{ request('pole_size') == '12 meter' ? 'selected' : '' }}>12 Meter</option>
                </select>
            </div>

            <div class="filter-group">
                <select name="year" class="form-select shadow-sm" onchange="this.form.submit()">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <a href="{{ route('map') }}" class="btn-reset shadow-sm" title="Reset Filter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <div id="map"></div>

    <div class="map-legend">
        <h6 class="mb-2 fw-bold">Legenda</h6>
        <div class="legend-item">
            <span class="dot" style="background-color: #2aad27;"></span> Tiang 9 meter
        </div>
        <div class="legend-item">
            <span class="dot" style="background-color: #9c27b0;"></span> Tiang 12 meter
        </div>
        <div class="legend-item">
            <span class="dot" style="background-color: #2ca0fa;"></span> Default/Lainnya
        </div>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleFilterBtn');
        const filterContainer = document.getElementById('filterContainer');

        toggleBtn.addEventListener('click', function() {
            filterContainer.classList.toggle('filter-hidden');
            this.classList.toggle('active');
        });

        const recapData = document.body.getAttribute('data-recaps');
        const recaps = JSON.parse(recapData);

        let initialCoords = [3.55, 98.86]; 
        if (recaps.length > 0 && recaps[0].x_cord) {
            initialCoords = [recaps[0].x_cord, recaps[0].y_cord];
        }

        const map = L.map('map').setView(initialCoords, 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const createIcon = (colorUrl) => new L.Icon({
            iconUrl: colorUrl,
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const greenIcon = createIcon('https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png');
        const purpleIcon = createIcon('https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png');
        const defaultIcon = createIcon('https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png');

        const markers = []; 

        recaps.forEach(recap => {
            if (recap.x_cord && recap.y_cord) {
                const coordinates = [recap.x_cord, recap.y_cord];
                
                let icon = defaultIcon;
                if (recap.contract && recap.contract.pole_size === '9 meter') {
                    icon = greenIcon;
                } else if (recap.contract && recap.contract.pole_size === '12 meter') {
                    icon = purpleIcon;
                }

                // --- PEMBARUAN DI SINI ---
                const popupContent = `
                    <div style="font-family: sans-serif;">
                        <strong>${recap.job}</strong><br>
                        <hr style="margin: 5px 0;">
                        <small>📍 ${recap.address}</small><br>
                        <small>📄 Jenis Kontrak: ${recap.contract?.name || recap.contract || '-'}</small><br>
                        <small>🏢 ${recap.contract?.company?.name || 'Unknown PT'}</small><br>
                        <small>📏 ${recap.contract?.pole_size || '-'}</small><br>
                        <small>📅 ${recap.created_at ? new Date(recap.created_at).getFullYear() : '-'}</small>
                    </div>
                `;

                const marker = L.marker(coordinates, { icon: icon })
                    .addTo(map)
                    .bindPopup(popupContent);
                
                markers.push(marker);
            }
        });

        if (markers.length > 0) {
            const group = new L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        }
    </script>
</body>

</html>
@endsection 