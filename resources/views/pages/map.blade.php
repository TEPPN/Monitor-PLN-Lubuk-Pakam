@extends('components.appbar')

@section('title', 'Home')

@section('content')
<!DOCTYPE html>
<html>

<head>
    <title>Simulasi Pergerakan Bus USU</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100vh;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Koordinat awal (pusat peta di sekitar USU)
        const usuCoords = [3.566854, 98.659142];

        // Inisialisasi peta
        const map = L.map('map').setView(usuCoords, 15);

        // Tambahkan layer peta dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Tambahkan marker (pin) untuk bus
        const busIcon = L.icon({
            iconUrl: 'images/bus-icon.png', // Icon bus
            iconSize: [20, 20], // Ukuran icon
        });

        const busMarker = L.marker(usuCoords, { icon: busIcon }).addTo(map)
            .bindPopup('Tiang 9 meter').openPopup();

        // Simulasikan pergerakan bus
        let step = 0;
        const route = [
            [3.566056, 98.653221],//stadion mini
            
        ];

        function moveBus() {
            if (step < route.length - 1) {
                step++;
            } else {
                step = 0; // Kembali ke titik awal
            }

            // Pindahkan marker ke koordinat baru
            const newCoords = route[step];
            busMarker.setLatLng(newCoords);

            // Geser peta untuk mengikuti bus
            map.panTo(newCoords);

            // Ulangi setiap 2 detik
            setTimeout(moveBus, 2000);
        }

        // Mulai simulasi pergerakan bus
        moveBus();
    </script>
</body>

</html>
@endsection
