@extends('layouts.main')

@section('title') User @endsection


@section('headcontent') Edit User @endsection
{{-- @section('breadcrumb') Role @endsection --}}
@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form User</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="" method="post">
                {{ csrf_field() }}
              <div class="card-body">

                <div class="form-group">
                  <label for="exampleInputEmail1">Name</label>
                  <input type="text" name="name" value="{{ $getRecord->name }}" class="form-control" required id="exampleInputEmail1" placeholder="Input name">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" value="{{ $getRecord->email }}" readonly class="form-control" id="email" placeholder="Input Email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Input Password">
                    <code>(isi password jika ingin mengubah, abaikan jika tidak.)</code>
                </div>

                <div class="form-group">
                    <label for="role">Role</label>
                    <select required name="role_id" id="role" class="form-control">
                        <option value="">Select</option>
                        @foreach ($getRole as $value)
                            <option {{ ($getRecord->role_id == $value->id) ? 'selected' : '' }} value="{{ $value->id }}">{{ $value->name }}</option>
                        @endforeach
                    </select>
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

