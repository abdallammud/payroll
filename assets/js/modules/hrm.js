async function send_hrmPost(str, data) {
    let [action, endpoint] = str.split(' ');

    try {
        const response = await $.post(`${base_url}/app/hrm_controller.php?action=${action}&endpoint=${endpoint}`, data);
        return response;
    } catch (error) {
        console.error('Error occurred during the request:', error);
        return null;
    }
}
document.addEventListener("DOMContentLoaded", function() {
	// console.log('HRM is here')
	// $('#add_employee').modal('show');
	
	$(document).on('click', '.add-educationRow', function(e) {
	    e.preventDefault();
	    let prevRow = $(e.target).closest('.row');
	    // Hide all "Add" buttons
	    $('button.add-educationRow').css('display', 'none');
	    $('button.remove-educationRow').css('display', 'block');
	    let newRow = `<div class="row education-row">
	        <div class="col col-xs-12 col-md-6 col-lg-4">
	            <div class="form-group">
	                <input type="text" class="form-control degree" id="degree" name="degree">
	                <span class="form-error text-danger">This is error</span>
	            </div>
	        </div>
	        <div class="col col-xs-12 col-md-6 col-lg-3">
	            <div class="form-group">
	                <input type="text" class="form-control institution" id="institution" name="institution">
	                <span class="form-error text-danger">This is error</span>
	            </div>
	        </div>
	        <div class="col col-xs-12 col-md-6 col-lg-2">
	            <div class="form-group">
	                <input type="text" class="form-control startYear" onkeypress="return isNumberKey(event)" id="startYear" name="startYear">
	                <span class="form-error text-danger">This is error</span>
	            </div>
	        </div>
	        <div class="col col-xs-12 col-md-6 col-lg-2">
	            <div class="form-group">
	                <input type="text" class="form-control endYear" onkeypress="return isNumberKey(event)" id="endYear" name="endYear">
	                <span class="form-error text-danger">This is error</span>
	            </div>
	        </div>
	        <div class="col col-xs-12 col-md-6 col-lg-1">
	            <div class="form-group">
	                <button type="button" class="btn form-control add-educationRow btn-info cursor" style="color: #fff;" >
                    	<i class="fa fa-plus-square"></i>
                    </button>
	                <button type="button" class="btn form-control remove-educationRow btn-danger cursor" style="display: none;">
	                	<i class="fa fa-trash"></i>
	                </button>
	            </div>
	        </div>
	    </div>`;

	    // Insert the new row after the current row
	    $(prevRow).after(newRow);
	});

	$(document).on('click', '.remove-educationRow', function(e) {
	    e.preventDefault();
	    let prevRow = $(e.target).closest('.row');
	    $(prevRow).fadeOut(500, function() {
	        $(this).remove();
	    });
	});

	$('#addEmployeeForm').on('submit', (e) => {
		handle_addEmployeeForm(e.target);
		return false
	})

});	

async function handle_addEmployeeForm(form) {
	clearErrors();
    let employeeName 	= $(form).find('#employeeName').val();
    let employeePhone 	= $(form).find('#employeePhone').val();
    let employeeEmail 	= $(form).find('#employeeEmail').val();
    let nationalID 		= $(form).find('#nationalID').val();
    let gender 			= $(form).find('#gender').val();
    let dob 			= $(form).find('#dob').val();
    let address 		= $(form).find('#address').val();
    let country 		= $(form).find('#country').val();
    let countryName 	= $(form).find('#country option:selected').text();
    let state 			= $(form).find('#state').val();
    let city 			= $(form).find('#city').val();
    let postalCode 		= $(form).find('#postal-code').val();
    let jobTitle 		= $(form).find('#jobTitle').val();
    let employeeDep 	= $(form).find('#employeeDep').val();
    let employeeDepName = $(form).find('#employeeDep option:selected').text();
    let JobType 		= $(form).find('#JobType').val();
    let hireDate 		= $(form).find('#hireDate').val();
    let employeeSalary 	= $(form).find('#employeeSalary').val();
    let bonus 			= $(form).find('#bonus').val();
    let contractStart 	= $(form).find('#contractStart').val();
    let contractEnd 	= $(form).find('#contractEnd').val();
    let degree 			= [];
    let institution 	= [];
    let startYear 		= [];
    let endYear 		= [];

    $('.row.education-row').each((i, el) => {
    	degree.push($(el).find('.degree').val());
    	institution.push($(el).find('.institution').val());
    	startYear.push($(el).find('.startYear').val());
    	endYear.push($(el).find('.endYear').val());
    })

     console.log(degree, institution,startYear, endYear)
    // return false;


    // Input validation
    let error = false;
    error = !validateField(employeeName, `Employee name is required`, 'employeeName') || error;
    
    error = !validateField(employeePhone, `Employee phone number is required`, 'employeePhone') || error;

    error = !validateField(gender, `Please select valid gender`, 'gender') || error;
    error = !validateField(jobTitle, `Job title is required`, 'jobTitle') || error;
    error = !validateField(JobType, `Please select valid job type`, 'JobType') || error;

    if (error) return false;

    let formData = {
        name: employeeName,
        phones: employeePhone,
        employeeEmail: employeeEmail,
        address: address,
		national_id: nationalID,
		gender: gender,
		dob: dob,
		country: country,
		countryName:countryName,
		state: state,
		city: city,
		postal_code: postalCode,
		job_title: jobTitle,
		branch_id: employeeDep,
		branch: employeeDepName,
		job_type: JobType,
		hire_date: hireDate,
		salary: employeeSalary,
		bonus:bonus,
		contractStart:contractStart,
		contractEnd:contractEnd,
		degree: degree,
		institution: institution,
		startYear: startYear,
		endYear: endYear
    };



    try {
        let response = await send_hrmPost('save employee', formData);
        console.log(response)

        if (response) {
            let res = JSON.parse(response)
            $('#add_branch').modal('hide');
            if(res.error) {
            	toaster.warning(res.msg, 'Sorry', { top: '30%', right: '20px', hide: true, duration: 5000 });
            } else {
            	toaster.success(res.msg, 'Success', { top: '20%', right: '20px', hide: true, duration:2000 }).then(() => {
            		// location.reload();
            		load_braches();
            	});
            	console.log(res)
            }
        } else {
            console.log('Failed to save branch.' + response);
        }

    } catch (err) {
        console.error('Error occurred during form submission:', err);
    }

    return false
}