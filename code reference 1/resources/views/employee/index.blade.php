@extends('layout')
@section('content')

<style>
  .push-top {
    margin-top: 50px;
  }
</style>

<div class="row">
  <h5 style="top: 3%;position: absolute;">Employee Salary Management</h5>
</div>
<div class="push-top">
  @if(session()->get('success'))
    <div class="alert alert-success" id="success-alert">
      {{ session()->get('success') }}
    </div><br />
  @endif

  <div class="row">
    <a href="{{ route('employees.create')}}" class="btn btn-primary btn-sm"">Add New</a>&nbsp;
  </div>

    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for=""></label>
          <select name="report" class="form-control" id="report">
          <option value="">--- Select ---</option>
          <option value="2nd_highest">2nd highest earning employee</option>
          <option value="5th_highest">5th highest earning employee</option>
          <option value="Avg_salary_by_dept">Avg salary by department</option>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label style="position: absolute;top: 40%;" id="display_report"></label>
        </div>
      </div>
    </div>

  <table class="table">
    <thead>
        <tr class="table-warning">
          <td>#</td>
          <td>Name</td>
          <td>Department</td>
          <td>Salary</td>
          <td>Hobbies</td>
          <td>Gender</td>
          <td class="text-center">Action</td>
        </tr>
    </thead>
    <tbody>
        @foreach($employee as $emp)
        <tr>
            <td>{{ $emp->id }}</td>
            <td>{{ $emp->name }}</td>
            <td>{{ $emp->department_name->name }}</td>
            <td>{{ $emp->salary }}</td>
            <td>{{ $emp->hobbies }}</td>
            <td>{{ $emp->gender }}</td>
            <td class="text-center">
              <a href="{{ route('employees.edit', $emp->id)}}" class="btn btn-primary btn-sm"">Edit</a>
                <form action="{{ route('employees.destroy', $emp->id)}}" method="post" style="display: inline-block">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"" type="submit">Delete</button>
                  </form>
            </td>
        </tr>
        @endforeach
    </tbody>
  </table>
<div>

@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">

  $( document ).ready(function() {

    $("#report").on('change', function() {

      var type = $(this).val();

      if(type !='') {
        $.ajax({
          type: "POST",
          url: '/getReport',
          data: { "type":type, "_token": "{{ csrf_token() }}"  },
          dataType: 'html',
          async: false, 
          success: function (data) {
            $("#display_report").text(data);
          },

        });

      } else {
          $("#display_report").text('');
      }

    });

  });
</script>
@endsection

@endsection
