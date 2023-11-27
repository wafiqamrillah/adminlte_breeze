@props(['breadcrumbs' => null])
<x-root-layout
    x-ref="body"
    x-data="window.nav.make()"
    x-bind:class="{ 'sidebar-collapse' : collapsed }"
    x-on:resize.window="resize()"
    class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
        {{-- <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="https://adminlte.io/themes/v3/dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
        </div> --}}

        <!-- Navbar -->
        <livewire:layout.navigation />
        
        <!-- Main Sidebar Container -->
        <livewire:layout.sidebar />

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if (isset($header))
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <!-- Header Title -->
                        <div class="col-sm-6">
                            <h1>{{ $header }}</h1>
                        </div>

                        <!-- Header Breadcrumbs -->
                        <div class="col-sm-6">
                            {{ $breadcrumbs }}
                        </div>
                    </div>
                </div>
            </section>
            @endif

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    {{ $slot }}
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; {{ date('Y') }} <a href="https://github.com/wafiqamrillah">Ahmad Wafiq Amrillah</a>.</strong>
        </footer>

        <!-- Sidebar Overlay -->
        <div id="sidebar-overlay" x-on:click="toggle()"></div>
    </div>
</x-root-layout>
