@extends('layouts.main')

@section('title') Role @endsection


@section('headcontent') Edit Role @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-10">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Role</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="post">
                {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" value="{{ $getRecord->name }}" name="name" class="form-control" id="exampleInputEmail1" placeholder="Input name">
                </div>

                <div class="form-group">
                    <label style="display: block; margin-bottom: 20px" for="exampleInputEmail1"> Permission</label>
                    @foreach ($getPermission as $value)

                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-md-2">
                                {{ $value['name'] }}
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    @foreach ($value['group'] as $group)
                                        @php
                                            $checked = '';
                                        @endphp
                                        @foreach ($getPermissionRole as $role)
                                            @if ($role->permission_id == $group['id'])
                                                @php
                                                    $checked = 'checked';
                                                @endphp
                                            @endif
                                        @endforeach
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" {{ $checked }} value="{{ $group['id'] }}" name="permission_id[]" class="custom-control-input" id="checkbox-{{ $group['name'] }}">
                                                <label for="checkbox-{{ $group['name'] }}" class="custom-control-label">{{ $group['name'] }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Update</button>
              </div>
            </form>
          </div>
        <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
@endsection

