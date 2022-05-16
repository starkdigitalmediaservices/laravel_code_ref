@extends('layout')

@section('content')

<style>
    .container {
      max-width: 550px;
    }
    .push-top {
      margin-top: 50px;
    }
    .error{
      color: #dc3545;
    }
</style>

<div class="card push-top">
  <div class="card-header">
    Employee
  </div>

  <div class="card-body">
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
        </ul>
      </div><br />
    @endif
      <form method="post" action="{{ route('employees.store') }}" id="createUpdate" >
          <div class="form-group">
              @csrf
              <label for="name">Name</label>
              <input type="text" class="form-control" name="name" />
          </div>
          <div class="form-group">
              <label for="salary">Salary</label>
              <input type="text" class="form-control" name="salary"/>
          </div>

          <div class="form-group">
            <label for="email">Gender</label></br>
              Male <input type="radio" name="gender" value="m" checked="checked" >
              Female <input type="radio" name="gender" value="m">
          </div>

          <div class="form-group">
              <label for="departments_id">Department</label>
              <select name="departments_id" class="form-control">
                  <option value="">--- Select ---</option>
                  @foreach ($getDeptList as $key => $value)
                  <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group">
              <label for="hobbies">Hobbies</label> <br>
               <label>Reading</label> <input type="checkbox" name="hobby[]" value="1">
              Cricket <input type="checkbox" name="hobby[]" value="2">
              Surfing <input type="checkbox" name="hobby[]" value="3">
              Swimming <input type="checkbox" name="hobby[]" value="4">
              <br>
              Watching Movies <input type="checkbox" name="hobby[]" value="5">
          </div>
          <div class="form-group">
            <label class="error-place"></label>
          </div>

          <button type="submit" class="btn btn-block btn-danger">Create</button>
      </form>
  </div>
</div>

@section('script')
  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/dist/jquery.validate.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/dist/jquery.validate.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/custome-validations/employeeCreateEdit.js') }}"></script>

  

@endsection

@endsection
