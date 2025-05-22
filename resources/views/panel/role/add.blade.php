@extends('layouts.main')

@section('title') Role @endsection


@section('headcontent') Add Role @endsection
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
            <form action="{{ url('panel/role/add') }}" method="post">
                {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="name" class="form-control" id="exampleInputEmail1" placeholder="Input name">
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
                                        <div class="col-md-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" value="{{ $group['id'] }}" name="permission_id[]" class="custom-control-input" id="checkbox-{{ $group['name'] }}">
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
                <button type="submit" class="btn btn-primary float-right">Submit</button>
              </div>
            </form>
          </div>
        <!-- /.card -->
    </div>
  </div>
  <!-- /.row -->
@endsection

