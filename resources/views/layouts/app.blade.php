@props(['breadcrumbs' => null])
<x-root-layout
    x-ref="body"
    x-data="{
        collapsed : $persist(false),
        resize() {
            this.collapsed = window.innerWidth <= 1024;
        },
        toggle() {
            this.collapsed = !this.collapsed;
            if (!this.collapsed) {
                this.$refs.body.classList.remove('sidebar-open');
            } else {
                this.$refs.body.classList.add('sidebar-open');
            }
        },
        clickAway() {
            if (window.innerWidth <= 1024) {
                this.collapsed = true;
                this.$refs.body.classList.remove('sidebar-open');
            }
        }
    }"
    x-bind:class="{ 'sidebar-collapse' : collapsed }"
    x-on:resize.window="resize()"
    class="sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">
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

        <!-- Wire Elements Modal -->
        @livewire('wire-elements-modal')
    </div>
</x-root-layout>
