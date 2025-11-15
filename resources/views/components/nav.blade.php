<ul class="nav flex-column mt-3">
  <li class="nav-item">
    <a href="{{ url('/') }}" class="nav-link text-dark">Beranda</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('map') }}" class="nav-link text-dark">Manajemen Peta</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('company.index') }}" class="nav-link text-dark">Daftar PT</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('contract.index') }}" class="nav-link text-dark">Daftar Kontrak</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('recap.index') }}" class="nav-link text-dark">Rekap Permintaan Tiang</a>
  </li>
  <li class="nav-item">
    <a href="{{ url('/log') }}" class="nav-link text-dark">Log</a>
  </li>
</ul>