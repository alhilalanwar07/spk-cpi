<div>


    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
            <h2 class="h4">Profil</h2>
            <p class="mb-0"></p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-4">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow border-0 text-center p-0">
                        <div class="profile-cover rounded-top" data-background="{{ url('/') }}/assets/img/favicon/favicon-32x32.png" ></div>
                        <div class="card-body pb-5">
                            <img src="{{ url('/') }}/no_image.jpg" class="avatar-xl rounded-circle mx-auto mt-n7 mb-4" alt="Neil Portrait">
                            <h4 class="h3">
                                {{ Auth::check() ? Auth::user()->name : '' }}
                            </h4>
                            <h5 class="fw-normal">
                                {{ Auth::check() ? Auth::user()->email : '' }}
                            </h5>
                            <p class="text-gray mb-4 badge bg-primary text-uppercase">
                                {{ Auth::check() ? Auth::user()->role : '' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card card-body border-0 shadow mb-4">
                <h2 class="h5 mb-4">Update Profil</h2>
                <form>
                    @csrf
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="name">Nama</label>
                                <input class="form-control" id="name" type="text" wire:model="name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="email">Email</label>
                                <input class="form-control" id="email" type="text" wire:model="email" value="{{ Auth::check() ? Auth::user()->email : '' }}" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div>
                                <label for="password">Password</label>
                                <input class="form-control" id="password" type="text" wire:model="password" >
                                <small>* Kosongkan jika tidak ingin mengganti password</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-gray-800 mt-2 animate-up-2" type="submit" wire:click.prevent="update" >Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @livewire('alert')
</div>