@extends('components.appbar')

@section('title', 'Peta Pekerjaan')

@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Peta Sebaran Pekerjaan</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100vh;
        }
    </style>
</head>

<body data-recaps="{{ json_encode($recaps) }}">
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Safely get recap data from the body's data attribute
        const recapData = document.body.getAttribute('data-recaps');
        const recaps = JSON.parse(recapData);

        // Set initial map coordinates. Default to a central point if no recaps exist.
        const initialCoords = recaps.length > 0 && recaps[0].x_cord && recaps[0].y_cord ? [recaps[0].x_cord, recaps[0].y_cord] : [3.59, 98.67];

        // Inisialisasi peta
        const map = L.map('map').setView(initialCoords, 13);

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Define custom icons for different pole sizes
        const greenIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const purpleIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-violet.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        const defaultIcon = new L.Icon({
            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Loop through each recap and add a marker to the map
        recaps.forEach(recap => {
            if (recap.x_cord && recap.y_cord) {
                const coordinates = [recap.x_cord, recap.y_cord];
                const popupContent = `<b>Pekerjaan:</b> ${recap.job}<br><b>Alamat:</b> ${recap.address}`;

                let icon = defaultIcon;
                if (recap.contract && recap.contract.pole_size === '9 meter') {
                    icon = greenIcon;
                } else if (recap.contract && recap.contract.pole_size === '12 meter') {
                    icon = purpleIcon;
                }

                L.marker(coordinates, { icon: icon })
                    .addTo(map)
                    .bindPopup(popupContent);
            }
        });
    </script>
</body>

</html>
@endsection
