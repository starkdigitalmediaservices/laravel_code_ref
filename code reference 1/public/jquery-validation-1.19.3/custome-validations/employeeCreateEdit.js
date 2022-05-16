$( document ).ready(function() {

      $("#createUpdate").validate({
            rules: {
              name: {
                required: true,
                minlength: 3,
                maxlength: 100
              },

              salary: {
                required: true,
                number: true,
                min: 2,
              },

              'hobby[]': {
                required: true,
                maxlength:3,
                minlength:1
              },

              departments_id: {
                required: true,
              }

            },
            messages: {
              name:{
                required:  "Name required!",
                minlength: "Minimum 3 latter required",
              },

              salary: "Salary required!",
              departments_id : "Departments required!",
              'hobby[]': { 
                required: "Please check at least 1 hobby",
                maxlength: "You can't check more then {0} hobbies",
                minlength: "Please check at least {0} hobby"
              } 
            },

            errorPlacement: function(error, element) {
                if (element.attr("name") == "hobby[]" )
                      error.insertAfter(".error-place");
                else
                error.insertAfter(element);
            }

          });


        

  });