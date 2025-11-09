<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'My App')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">

</head>
<body>
  <div class="appbar d-flex align-items-center bg-primary text-white py-2 px-3 shadow-sm">
    <button id="toggleSidebar" class="btn btn-light btn-sm me-3">☰</button>
    <img src="{{ asset('assets/icon/pln.svg') }}" alt="Logo" width="40" height="40" class="me-2">
    <h1 class="h5 mb-0">PLN LUBUK PAKAM</h1>
  </div>

  <div id="sidebar" class="sidebar">
    @include('components.nav')
  </div>

  <div id="overlay" class="overlay"></div>

  <div id="content" class="content">
    @yield('content')
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const toggleBtn = document.getElementById('toggleSidebar');
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('overlay');
      const content = document.getElementById('content');

      function toggleSidebar() {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('show');
        content.classList.toggle('dimmed');
      }

      toggleBtn.addEventListener('click', toggleSidebar);
      overlay.addEventListener('click', toggleSidebar);
    });
  </script>
</body>
</html>
