<x-app-layout>
    <x-slot name="header">
        <h1 class="m-0">
            {{ __('Profile') }}
        </h1>
    </x-slot>

    <div
        x-data="{
            tab: window.location.hash ? window.location.hash.substring(1) : 'edit-profile-information',
            selectTab(tab) {
                this.tab = tab;
                
                $el.querySelectorAll('.nav-link').forEach((link) => {
                    if (link.getAttribute('href') === `#${tab}`) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                });

                $el.querySelectorAll('.tab-pane').forEach((el) => {
                    if (el.getAttribute('id') === tab) {
                        el.classList.add('active');
                        setTimeout(() => el.classList.add('show'), 50);
                    } else {
                        el.classList.remove('active');
                        el.classList.remove('show');
                    }
                });
            }
        }"
        x-init="() => {
            selectTab(tab);
        }"
        class="row">
        <div class="col-lg-6 col-xl-4">
            <!-- Account Summary -->
            <div class="card card-widget widget-user-2">
                <div class="widget-user-header bg-primary">
                    <div class="widget-user-image">
                        <img src="https://adminlte.io/themes/v3/dist/img/user2-160x160.jpg" class="elevation-2 img-circle" alt="User profile picture">
                    </div>
                    <h3 class="widget-user-username">
                        {{ auth()->user()->name }}
                    </h3>
                    <h6 class="widget-user-desc">
                        {{ auth()->user()->email }}
                    </h6>
                </div>

                <div class="card-footer">
                    <ul class="nav nav-pills flex-column" role="tablist">
                        <li class="nav-item">
                            <a
                                x-on:click.prevent="selectTab('edit-profile-information')"
                                href="#edit-profile-information"
                                class="nav-link"
                                data-toggle="pill">
                                {{ __('Edit Profile Information') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                x-on:click.prevent="selectTab('update-password')"
                                href="#update-password"
                                class="nav-link"
                                data-toggle="pill">
                                {{ __('Update Password') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a
                                x-on:click.prevent="selectTab('delete-user')"
                                href="#delete-user"
                                class="nav-link"
                                data-toggle="pill">
                                {{ __('Delete User') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-xl-8">
            <div class="card card-primary card-outline">
                <!-- Card Body -->
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Update Profile Information -->
                        <div
                            id="edit-profile-information"
                            class="tab-pane fade"
                            >
                            <livewire:profile.update-profile-information-form />
                        </div>
    
                        <!-- Update Password -->
                        <div
                            id="update-password"
                            class="tab-pane fade">
                            <livewire:profile.update-password-form />
                        </div>
    
                        <!-- Delete User -->
                        <div
                            id="delete-user"
                            class="tab-pane fade">
                            <livewire:profile.delete-user-form />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
