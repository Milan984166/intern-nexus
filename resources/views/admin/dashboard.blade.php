@extends('admin/layouts.header-sidebar')
@section('title','Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-md-6 col-sm-12">
                    <h2>Dashboard</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 col-sm-12 text-right hidden-xs">
                </div>
            </div>
        </div>
        <div class="card planned_task">
            <div class="body bg-transparent">
                <div class="row">

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.setting') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-settings"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Site Setting</h5>
                                        <small class="subtitle">Update Setting</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.users') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-users"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">All Users</h5>
                                        <small class="subtitle">Add || Edit & Update Users</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.job-seekers') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-users"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Job Seekers</h5>
                                        <small class="subtitle">Add || Edit & Update Job Seekers</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.employers') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-users"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Employers</h5>
                                        <small class="subtitle">Add || Edit & Update Employers</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.sliders') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-layers"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Sliders</h5>
                                        <small class="subtitle">Add || Edit & Update Sliders</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.pages') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Pages</h5>
                                        <small class="subtitle">Add || Edit & Update Pages</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.job_categories') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="fa fa-anchor"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Job Categories</h5>
                                        <small class="subtitle">Add || Edit & Update Job Categories</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-xl-4">
                        <a href="{{ route('admin.locations') }}">
                            <div class="card mb-3">
                                <div class="body top_counter">
                                    <div class="icon text-white" style="background-color: #0099ae;">
                                        <i class="icon-pointer"></i>
                                    </div>
                                    <div class="content">
                                        <h5 class="number mb-0">Locations</h5>
                                        <small class="subtitle">Add || Edit & Update Locations</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

