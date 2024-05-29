@extends('admin/layouts.header-sidebar')
@section('title', $id != 0 ? $employer->name : 'Employers')
@section('content')
<div class="container-fluid">
    <div class="block-header">
        <div class="row clearfix">
            <div class="col-md-6 col-sm-12">
                <h2>Employer's List</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard')  }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.employers') }}">Employers</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Employer's List</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right hidden-xs">
                <a href="{{ $id != 0 ? route('admin.employers') : route('dashboard') }}" class="btn btn-sm btn-outline-primary" title="Go Back">
                    <i class="fa fa-angle-double-left"></i> Go Back
                </a>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12">
            <div class="card">
                <ul class="nav nav-tabs2">
                    @if($id == 0)
                    <li class="nav-item"><a class="nav-link show active" data-toggle="tab" href="#Employers">Employers</a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ $id != 0 ? 'show active' : '' }}" data-toggle="tab" href="#addEmployer">
                            {{ $id == 0 ? 'Add Employer' : 'Update Employer | '.$employer->name }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content mt-0">
                    @if($id == 0)
                    <div class="tab-pane show active" id="Employers">
                        <div class="table-responsive">
                            <table id="only-bodytable" class="table table-hover table-custom spacing8">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="w60"></th>
                                        <th>Full Name</th>
                                        <th>Role</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th class="w100">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employers as $employer)
                                    <tr>
                                        <td><i class="table-dragger-handle sindu_handle"></i></td>
                                        <td class="width45">
                                            @if($employer->image != '')
                                            <div class="avtar-pic w35" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ $employer->name }}">

                                                <img src="{{ asset('storage/employers/thumbs/small_'.$employer->image) }}" class="avtar-pic w35" alt="no-image">
                                                
                                            </div>
                                            @else
                                                @php
                                                $parts = explode(' ', $employer->name);
                                                @endphp
                                                <div class="avtar-pic w35 @if($employer->role == 1) bg-pink @else bg-blue @endif "
                                                    data-toggle="tooltip"
                                                    data-placement="top" title=""
                                                    data-original-title="{{ $employer->name }}">
                                                    <span>
                                                        @for($i=0; $i < count($parts); $i++)
                                                        {{ strtoupper(substr($parts[$i], 0, 1)) }} 
                                                        @endfor
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <h6 class="mb-0">
                                                {{ isset($employer->employer_info->organization_name) ? $employer->employer_info->organization_name : $employer->name }}
                                                [ {{ $employer->jobs->count() }} ]
                                            </h6>
                                            <span>{{ $employer->email }}</span>
                                        </td>
                                        <td>
                                            @if($employer->role == 1)
                                            <span class="badge badge-success">Super Admin</span>
                                            @elseif($employer->role == 2)
                                            <span class="badge badge-primary">Employer</span>
                                            @elseif($employer->role == 3)
                                            <span class="badge badge-danger">Sole Trader</span>
                                            @elseif($employer->role == 4)
                                            <span class="badge badge-warning">Company</span>
                                            @endif
                                        </td>

                                        <td>{{ date('jS F, Y',strtotime($employer->created_at)) }}</td>
                                        <td>
                                            @if($employer->status == 1)
                                            <span class="badge badge-success">Active</span>
                                            @else
                                            <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="#viewModal" class="btn btn-sm btn-outline-success" data-toggle="modal"
                                            data-id="{{ $employer->id }} "
                                            id="view{{ $employer->id }}"
                                            data-email="{{ $employer->email }}"
                                            data-gender="{{ $employer->gender }}"
                                            data-phone="{{ $employer->phone }}"
                                            data-current_address="{{ $employer->current_address }}"
                                            data-permanent_address="{{ $employer->permanent_address }}"
                                            data-dob="{{ date('jS F,Y',strtotime($employer->dob)) }}"
                                            data-religion="{{ $employer->religion }}"
                                            data-nationality="{{ $employer->nationality }}"
                                            data-maritial_status="{{ $employer->maritial_status }}"
                                            onclick="view_employer('{{ $employer->id }}','{{ addslashes($employer->name) }}','{{ $employer->status }}')"
                                            title="View">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ url('admin/employers/profile/'.base64_encode($employer->id)) }}" class="btn btn-sm btn-info" title="View User's Profile">
                                            <i class="fa fa-eye"></i> View Profile
                                        </a>

                                        <a href="{{ url('admin/employers/edit/'.base64_encode($employer->id)) }}" class="btn btn-sm btn-outline-primary" title="Edit Basic Details">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($employer->id != \Illuminate\Support\Facades\Auth::user()->id)

                                        <a href="#delete" data-toggle="modal" data-id="{{ $employer->id }}" id="delete{{ $employer->id }}" class="btn btn-sm btn-outline-danger" title="Delete" onclick="delete_employer('{{ $employer->id }}')">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="tab-pane {{ $id != 0 ? 'show active' : '' }}" id="addEmployer">
                    <div class="body mt-2">
                        <form method="post" action="{{ $id == 0 ? route('admin.employers.create') : route('admin.employers.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="userId" name="id" value="{{ $id != 0 ? $id : '' }}"/>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-text-width"></i> &nbsp; Name
                                            </span>
                                        </div>
                                        <input type="text" name="name" class="form-control" required value="{{ $id != 0 ? $employer->name : old('name') }}" placeholder="eg: John Doe">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-envelope"></i> &nbsp;Email
                                            </span>
                                        </div>
                                        <input type="email" name="email" class="form-control" required value="{{ $id != 0 ? $employer->email : old('email') }}" <?= $id != 0 ? "disabled readonly" : "" ?> placeholder="eg: hello@example.com">
                                    </div>
                                </div>
                                @if($id == 0)
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-lock"></i> &nbsp;Password
                                            </span>
                                        </div>
                                        <input type="password" name="password" placeholder="*******" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-lock"></i> &nbsp;Re-Type Password
                                            </span>
                                        </div>
                                        <input type="password" name="password_confirmation" placeholder="*******" class="form-control" required>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed"><i class="fa fa-user"></i>&nbsp;  Role</label>
                                        </div>
                                        <select class="custom-select" name="role" required>
                                            <option disabled selected="" value="">Choose User's Role...</option>

                                            <option value="3" {{ $id != 0 && $employer->role == 3 ? 'selected' : (old('role') == 3 ? 'selected' : '') }}>Sole Trader</option>

                                            <option value="4" {{ $id != 0 && $employer->role == 4 ? 'selected' : (old('role') == 4 ? 'selected' : '') }}>Company</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text" style="background-color: #e1e8ed">
                                                <?php $status =  $id != 0 ? $employer->status : 0  ?>
                                                <input type="checkbox" name="status" value="1" aria-label="Checkbox for following text input" <?=$status == 1 ? 'checked' : (old('status') == 1 ? 'checked' : '') ?>>
                                            </div>
                                        </div>
                                        <input type="button " class="form-control bg-indigo text-muted" value="Employer's Status" disabled>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-text-width"></i> &nbsp;Mobile Number
                                            </span>
                                        </div>
                                        <input type="text" name="phone" class="form-control" required value="{{ $id != 0 ? $employer->phone : old('phone') }}" placeholder="eg: 9876543210" pattern="^(\+\d{3}-\d{7,10})|(\d{7,10})$" oninvalid="this.setCustomValidity('Enter Valid Mobile Number!!')" oninput="this.setCustomValidity('')">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-calendar"></i> &nbsp;Date of Birth
                                            </span>
                                        </div>
                                        <input type="text" name="dob" class="form-control" value="{{ $id != 0 ? $employer->dob : old('dob') }}" data-provide="datepicker" data-date-autoclose="true" class="form-control" data-date-format="yyyy-mm-dd">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-map-marker"></i> &nbsp;Current Address
                                            </span>
                                        </div>
                                        <input type="text" name="current_address" class="form-control" required value="{{ $id != 0 ? $employer->current_address : old('current_address') }}" placeholder="eg: Kathmandu, Nepal">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" style="min-width: 100px; background-color: #e1e8ed">
                                                <i class="fa fa-map-marker"></i> &nbsp;Permanent Address
                                            </span>
                                        </div>
                                        <input type="text" name="permanent_address" class="form-control" required value="{{ $id != 0 ? $employer->permanent_address : old('permanent_address') }}" placeholder="eg: Kathmandu, Nepal">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed"><i class="fa fa-user"></i>&nbsp; Nationality</label>
                                        </div>
                                        <select class="custom-select" name="nationality" required>
                                            <option selected="" value="">Choose...</option>
                                            @php
                                            $nationalities = DB::table('nationalities')->get();
                                            @endphp
                                            @foreach($nationalities as $nation)
                                            <option value="{{ $nation->Nationality }}" {{ $id != 0 && $employer->nationality == $nation->Nationality ? 'selected' : (old('nationality') == $nation->Nationality ? 'selected' : '') }}>{{ $nation->Nationality }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed"><i class="fa fa-user"></i>&nbsp; Religion</label>
                                        </div>
                                        <select class="custom-select" name="religion">
                                            <option selected="" value="">Choose...</option>
                                            
                                            <option value="Hinduism" {{ $id != 0 && $employer->religion == 'Hinduism' ? 'selected' : (old('religion') == 'Hinduism' ? 'selected' : '') }}>Hinduism</option>

                                            <option value="Buddism" {{ $id != 0 && $employer->religion == 'Buddism' ? 'selected' : (old('religion') == 'Buddism' ? 'selected' : '') }}>Buddism</option>

                                            <option value="Christianity" {{ $id != 0 && $employer->religion == 'Christianity' ? 'selected' : (old('religion') == 'Christianity' ? 'selected' : '') }}>Christianity</option>

                                            <option value="Jainism" {{ $id != 0 && $employer->religion == 'Jainism' ? 'selected' : (old('religion') == 'Jainism' ? 'selected' : '') }}>Jainism</option>

                                            <option value="NonReligious" {{ $id != 0 && $employer->religion == 'NonReligious' ? 'selected' : (old('religion') == 'NonReligious' ? 'selected' : '') }}>NonReligious</option>

                                            <option value="Sikhism" {{ $id != 0 && $employer->religion == 'Sikhism' ? 'selected' : (old('religion') == 'Sikhism' ? 'selected' : '') }}>Sikhism</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed"><i class="fa fa-user"></i>&nbsp; Gender</label>
                                        </div>
                                        <select class="custom-select" name="gender" required>
                                            <option selected="" value="">Choose...</option>
                                            <option value="1" {{ $id != 0 && $employer->gender == 1 ? 'selected' : (old('gender') == 1 ? 'selected' : '') }}>Male</option>
                                            <option value="2" {{ $id != 0 && $employer->gender == 2 ? 'selected' : (old('gender') == 2 ? 'selected' : '') }}>Female</option>
                                            <option value="3" {{ $id != 0 && $employer->gender == 3 ? 'selected' : (old('gender') == 3 ? 'selected' : '') }}>Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" style="min-width: 100px; background-color: #e1e8ed"><i class="fa fa-user"></i>&nbsp; Maritial Status</label>
                                        </div>
                                        <select class="custom-select" name="maritial_status">
                                            <option selected="" value="">Choose...</option>
                                            
                                            <option value="Married" {{ $id != 0 && $employer->maritial_status == 'Married' ? 'selected' : (old('maritial_status') == 'Married' ? 'selected' : '') }}>Married</option>

                                            <option value="Unmarried" {{ $id != 0 && $employer->maritial_status == 'Unmarried' ? 'selected' : (old('maritial_status') == 'Unmarried' ? 'selected' : '') }}>Unmarried</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">  
                                    <small class="text-muted">
                                        <i class="fa fa-building"></i> &nbsp; Profile Image*
                                    </small>
                                    <div class="input-group mb-3">
                                        <input type="file" name="image" class="bg-primary text-white form-control" {{ ( $id == 0 || $employer->image == '' ? 'required' : '' ) }}>
                                    </div>
                                    <hr>
                                </div>

                                <div class="col-md-12">
                                    @if ($id != 0)
                                    <a href="{{ route('admin.employers') }}"
                                    class="btn btn-outline-danger">CANCEL</a>

                                    <button type="submit" style="float: right;" class="btn btn-outline-success"> UPDATE</button>
                                    @else
                                    <button type="submit" style="float: right;" class="btn btn-outline-success"> SAVE</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade " id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6><span class="viewName"></span><span class="mb-0" id="viewStatus"></span></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body pricing_page">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-user"></i> Name: </small>
                                        <p class="mb-0 viewName"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-envelope"></i> Email: </small>
                                        <p class="mb-0" id="viewEmail"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-male"></i><i class="fa fa-female"></i> Gender: </small>
                                        <p class="mb-0" id="viewGender"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-phone"></i> Contact Number: </small>
                                        <p class="mb-0" id="viewPhone"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-user"></i> Maritial Status: </small>
                                        <p class="mb-0" id="viewMaritialStatus"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-0">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> Current Address: </small>
                                        <p class="mb-0" id="viewCurrentAddress"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-map-marker"></i> Permanent Address: </small>
                                        <p class="mb-0" id="viewPermanentAddress"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-calendar"></i> Date of Birth: </small>
                                        <p class="mb-0" id="viewDOB"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-location-arrow"></i> Religion: </small>
                                        <p class="mb-0" id="viewReligion"></p>
                                    </li>
                                    <li class="list-group-item">
                                        <small class="text-muted"><i class="fa fa-globe"></i> Nationality: </small>
                                        <p class="mb-0" id="viewNationality"></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="text-align: right;" type="button" data-dismiss="modal"
                    class="btn btn-outline-danger">Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="delete" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6>Delete Employer</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body pricing_page">
                <p>Are your Sure?</p>
            </div>
            <div class="modal-footer">
                <a style="text-align: right;" type="button" class="btn btn-outline-success" href="">Yes, Delete It!</a>
                <button style="text-align: left;" type="button" data-dismiss="modal" class="btn btn-outline-danger">No</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function view_employer(id, name, status) {
        var email = $('#view' + id).attr('data-email');
        var gender = $('#view' + id).attr('data-gender');
        var phone = $('#view' + id).attr('data-phone');
        var current_address = $('#view' + id).attr('data-current_address');
        var permanent_address = $('#view' + id).attr('data-permanent_address');
        var dob = $('#view' + id).attr('data-dob');
        var religion = $('#view' + id).attr('data-religion');
        var nationality = $('#view' + id).attr('data-nationality');
        var maritial_status = $('#view' + id).attr('data-maritial_status');
        $('.viewName').html(name);
        $('#viewEmail').html(email);
        $('#viewPhone').html(phone);
        $('#viewCurrentAddress').html(current_address);
        $('#viewPermanentAddress').html(permanent_address);
        $('#viewDOB').html(dob);
        $('#viewReligion').html(religion);
        $('#viewNationality').html(nationality);
        $('#viewMaritialStatus').html(maritial_status);

        if (gender == 1) {
            $('#viewGender').html('Male');
        }else if (gender == 2) {
            $('#viewGender').html('Female');
        }else if (gender == 3) {
            $('#viewGender').html('Others');
        }else{
            $('#viewGender').html('To be Updated');
        }
        if (status == 0) {
            $('#viewStatus').html('<span class="badge badge-danger">Inactive</span>');
        }else{
            $('#viewStatus').html('<span class="badge badge-success">Active</span>');
        }
    }

    function delete_employer(id) {
        var conn = './employers/delete/' + id;
        $('#delete a').attr("href", conn);
    }
</script>
@endsection
