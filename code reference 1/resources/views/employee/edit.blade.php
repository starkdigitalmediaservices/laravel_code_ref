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
              <input type="text" class="form-control" name="name" value="{{ $emloyeeData->name ?? '' }}" />
              <input type="hidden" name="id" value="{{ $emloyeeData->id ?? '' }}">
          </div>
          <div class="form-group">
              <label for="salary">Salary</label>
              <input type="text" class="form-control" name="salary" value="{{ $emloyeeData->salary ?? '' }}" />
          </div>

          <div class="form-group">
            <label for="email">Gender</label></br>
              Male <input type="radio" name="gender" value="m" 

                @if($emloyeeData->gender == 'Male' || $emloyeeData->gender == '' )
                 {{ 'checked="checked"'  }}
                @endif
                >
              Female <input type="radio" name="gender" value="f"
              @if($emloyeeData->gender == 'Female' )
                 {{ 'checked="checked"'  }}
                @endif>
          </div>

          <div class="form-group">
              <label for="departments_id">Department</label>
              <select name="departments_id" class="form-control">
                  <option value="">--- Select ---</option>
                  @foreach ($getDeptList as $key => $value)
                  <option value="{{ $key }}" 
                  @if($emloyeeData->departments_id == $key) 
                    selected
                  @endif 
                  >{{ $value }}</option>
                  @endforeach
              </select>
          </div>
          <div class="form-group">
              <label for="hobbies">Hobbies</label> <br>
              Reading <input type="checkbox" name="hobby[]" value="1" 
              @if (strpos($emloyeeData->getRawOriginal('hobbies'), 1) !== false)
                checked
              @endif
              >
              Cricket <input type="checkbox" name="hobby[]" value="2"
              @if (strpos($emloyeeData->getRawOriginal('hobbies'), 2) !== false)
                checked
              @endif>
              Surfing <input type="checkbox" name="hobby[]" value="3"
              @if (strpos($emloyeeData->getRawOriginal('hobbies'), 3) !== false)
                checked
              @endif
              >
              Swimming <input type="checkbox" name="hobby[]" value="4"
              @if (strpos($emloyeeData->getRawOriginal('hobbies'), 4) !== false)
                checked
              @endif
              >
              <br>
              Watching Movies <input type="checkbox" name="hobby[]" value="5"
              @if (strpos($emloyeeData->getRawOriginal('hobbies'), 5) !== false)
                checked
              @endif
              >
          </div>
          <div class="form-group">
            <label class="error-place"></label>
          </div>

          <button type="submit" class="btn btn-block btn-danger">Update</button>
      </form>
  </div>
</div>

@section('script')
  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/dist/jquery.validate.min.js') }}"></script>

  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/dist/jquery.validate.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('jquery-validation-1.19.3/custome-validations/employeeCreateEdit.js') }}"></script>

  

@endsection

@endsection
