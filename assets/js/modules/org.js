function load_comapny() {
	var datatable = $('#companyDT').DataTable({
		// let datatable = new DataTable('#companyDT', {
	    "processing": true,
	    "serverSide": true,
	    "bDestroy": true,
	    "columnDefs": [
	        { "orderable": false, "searchable": false, "targets": [4] }  // Disable search on first and last columns
	    ],
	    "serverMethod": 'post',
	    "ajax": {
	        "url": "./app/org_controller.php?action=load&endpoint=company",
	        "method": "POST",
		    // dataFilter: function(data) {
			// 	console.log(data)
			// }
	    },
	    
	    "drawCallback": function(settings) {
	        
	    },
	    columns: [
	        { title: "Organization Name", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.name}</span>
	                </div>`;
	        }},

	        { title: "Phone Numbers", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.contact_phone}</span>
	                </div>`;
	        }},

	        { title: "Emails", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.contact_email}</span>
	                </div>`;
	        }},

	        { title: "Address", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.address}</span>
	                </div>`;
	        }},

	        { title: "Action", data: null, render: function(data, type, row) {
	            return `<div class="sflex scenter-items">
	            		<span data-recid="${row.id}" class="fa edit_companyInfo smt-5 cursor smr-10 fa-pencil"></span>
	            		<span data-recid="${row.id}" class="fa delete_company smt-5 cursor fa-trash"></span>
	                </div>`;
	        }},
	    ]
	});

	return false;
}

async function send_orgPost(str, data) {
    let [action, endpoint] = str.split(' ');

    try {
        const response = await $.post(`./app/org_controller.php?action=${action}&endpoint=${endpoint}`, data);
        return response;
    } catch (error) {
        console.error('Error occurred during the request:', error);
        return null;
    }
}

async function handle_addCompanyForm(form) {
    clearErrors();

    let name = $(form).find('#orgName').val();
    let phones = $(form).find('#contactPhone').val();
    let emails = $(form).find('#contactEmail').val();
    let address = $(form).find('#txtAddress').val();

    // Input validation
    let error = false;
    error = !validateField(name, "Company name is required", 'orgName') || error;
    error = !validateField(phones, "Company phone number is required", 'contactPhone') || error;

    if (error) return false;

    let formData = {
        name: name,
        phones: phones,
        emails: emails,
        address: address
    };

    try {
        let response = await send_orgPost('save company', formData);

        if (response) {
            let res = JSON.parse(response)
            $('#add_org').modal('hide');
            if(res.error) {
            	toaster.warning(res.msg, 'Sorry', { top: '30%', right: '20px', hide: true, duration: 5000 });
            } else {
            	toaster.success(res.msg, 'Success', { top: '20%', right: '20px', hide: true, duration:2000 }).then(() => {
            		// location.reload();
            		load_comapny();
            	});
            	console.log(res)
            }
        } else {
            console.log('Failed to save company.' + response);
        }

    } catch (err) {
        console.error('Error occurred during form submission:', err);
    }
}

async function handle_editCompanyForm(form) {
    clearErrors();

    let id = $(form).find('#company_id').val();
    let name = $(form).find('#orgName4Edit').val();
    let phones = $(form).find('#contactPhone4Edit').val();
    let emails = $(form).find('#contactEmail4Edit').val();
    let address = $(form).find('#txtAddress4Edit').val();

    // Input validation
    let error = false;
    error = !validateField(name, "Company name is required", 'orgName4Edit') || error;
    error = !validateField(phones, "Company phone number is required", 'contactPhone4Edit') || error;

    if (error) return false;

    let formData = {
    	id:id,
        name: name,
        phones: phones,
        emails: emails,
        address: address
    };

    try {
        let response = await send_orgPost('update company', formData);

        if (response) {
            let res = JSON.parse(response)
            $('#edit_org').modal('hide');
            if(res.error) {
            	toaster.warning(res.msg, 'Sorry', { top: '30%', right: '20px', hide: true, duration: 5000 });
            } else {
            	toaster.success(res.msg, 'Success', { top: '20%', right: '20px', hide: true, duration:2000 }).then(() => {
            		// location.reload();
            		load_comapny();
            	});
            	console.log(res)
            }
        } else {
            console.log('Failed to edit company.' + response);
        }

    } catch (err) {
        console.error('Error occurred during form submission:', err);
    }
}

async function get_company(id) {
	let data = {id};
	let response = await send_orgPost('get company', data);
	return response;
}

// Branches
function load_braches() {
	var datatable = $('#branchesDT').DataTable({
		// let datatable = new DataTable('#companyDT', {
	    "processing": true,
	    "serverSide": true,
	    "bDestroy": true,
	    "columnDefs": [
	        { "orderable": false, "searchable": false, "targets": [4] }  // Disable search on first and last columns
	    ],
	    "serverMethod": 'post',
	    "ajax": {
	        "url": "./app/org_controller.php?action=load&endpoint=branches",
	        "method": "POST",
		    // dataFilter: function(data) {
			// 	console.log(data)
			// }
	    },
	    
	    "drawCallback": function(settings) {
	        
	    },
	    columns: [
	        { title: `${branch_keyword.sing} Name`, data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.name}</span>
	                </div>`;
	        }},

	        { title: "Phone Numbers", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.contact_phone}</span>
	                </div>`;
	        }},

	        { title: "Emails", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.contact_email}</span>
	                </div>`;
	        }},

	        { title: "Address", data: null, render: function(data, type, row) {
	            return `<div>
	            		<span>${row.address}</span>
	                </div>`;
	        }},

	        { title: "Action", data: null, render: function(data, type, row) {
	            return `<div class="sflex scenter-items">
	            		<span data-recid="${row.id}" class="fa edit_branchInfo smt-5 cursor smr-10 fa-pencil"></span>
	            		<span data-recid="${row.id}" class="fa delete_branch smt-5 cursor fa-trash"></span>
	                </div>`;
	        }},
	    ]
	});

	return false;
}

async function handle_addBranchForm(form) {
    clearErrors();

    let name = $(form).find('#branchName').val();
    let phones = $(form).find('#contactPhone').val();
    let emails = $(form).find('#contactEmail').val();
    let address = $(form).find('#txtAddress').val();

    // Input validation
    let error = false;
    error = !validateField(name, `${branch_keyword.sing} name is required`, 'branchName') || error;
    error = !validateField(phones, `${branch_keyword.sing} phone number is required`, 'contactPhone') || error;

    if (error) return false;

    let formData = {
        name: name,
        phones: phones,
        emails: emails,
        address: address
    };

    try {
        let response = await send_orgPost('save branch', formData);

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
}

async function handle_editBranchForm(form) {
    clearErrors();

    let id = $(form).find('#branch_id').val();
    let name = $(form).find('#branchName4Edit').val();
    let phones = $(form).find('#contactPhone4Edit').val();
    let emails = $(form).find('#contactEmail4Edit').val();
    let address = $(form).find('#txtAddress4Edit').val();

    // Input validation
    let error = false;
    error = !validateField(name, `${branch_keyword.sing} name is required`, 'branchName4Edit') || error;
    error = !validateField(phones, `${branch_keyword.sing} phone number is required`, 'contactPhone4Edit') || error;

    if (error) return false;

    let formData = {
    	id:id,
        name: name,
        phones: phones,
        emails: emails,
        address: address
    };

    try {
        let response = await send_orgPost('update branch', formData);

        if (response) {
            let res = JSON.parse(response)
            $('#edit_branch').modal('hide');
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
            console.log('Failed to edit company.' + response);
        }

    } catch (err) {
        console.error('Error occurred during form submission:', err);
    }
}

async function get_branch(id) {
	let data = {id};
	let response = await send_orgPost('get branch', data);
	return response;
}

document.addEventListener("DOMContentLoaded", function() {
	(function() {
		// console.log(branch_keyword)
	})();


	$('#addOrgForm').on('submit', (e) => {
		handle_addCompanyForm(e.target);
		return false
	})

	$('#addBranchForm').on('submit', (e) => {
		handle_addBranchForm(e.target);
		return false
	})

	load_comapny();
	load_braches();

	// edit company info popup
	$(document).on('click', '.edit_companyInfo', async (e) => {
	    let id = $(e.currentTarget).data('recid');
	    let modal = $('#edit_org');

	    let data = await get_company(id)
	    if(data) {
	    	let res = JSON.parse(data)[0]

	    	$(modal).find('#company_id').val(id)
	    	$(modal).find('#orgName4Edit').val(res.name)
	    	$(modal).find('#contactPhone4Edit').val(res.contact_phone) 
	    	$(modal).find('#contactEmail4Edit').val(res.contact_email)
	    	$(modal).find('#txtAddress4Edit').val(res.address)
	    }

	    $(modal).modal('show');
	});

	$('#editOrgForm').on('submit', (e) => {
		handle_editCompanyForm(e.target);
		return false
	})


	$(document).on('click', '.delete_company', async (e) => {
	    let id = $(e.currentTarget).data('recid');
	    swal({
	        title: "Are you sure?",
	        text: "You are going to delete this company record.",
	        icon: "warning",
	        className: 'warning-swal',
	        buttons: ["Cancel", "Yes, delete"],
	    }).then(async (confirm) => {
	        if (confirm) {
	            let data = { id: id };
	            try {
	                let response = await send_orgPost('delete company', data);

	                if (response) {
	                    let res = JSON.parse(response);
	                    if (res.error) {
	                        toaster.warning(res.msg, 'Sorry', { top: '30%', right: '20px', hide: true, duration: 5000 });
	                    } else {
	                        toaster.success(res.msg, 'Success', { top: '20%', right: '20px', hide: true, duration: 2000 }).then(() => {
	                            // location.reload();
	                            load_comapny();
	                        });
	                        console.log(res);
	                    }
	                } else {
	                    console.log('Failed to edit company.' + response);
	                }

	            } catch (err) {
	                console.error('Error occurred during form submission:', err);
	            }
	        }
	    });
	});

	// Branches
	$(document).on('click', '.edit_branchInfo', async (e) => {
	    let id = $(e.currentTarget).data('recid');
	    let modal = $('#edit_branch');

	    let data = await get_branch(id)
	    if(data) {
	    	let res = JSON.parse(data)[0]

	    	$(modal).find('#branch_id').val(id)
	    	$(modal).find('#branchName4Edit').val(res.name)
	    	$(modal).find('#contactPhone4Edit').val(res.contact_phone) 
	    	$(modal).find('#contactEmail4Edit').val(res.contact_email)
	    	$(modal).find('#txtAddress4Edit').val(res.address)
	    }

	    $(modal).modal('show');
	});

	$('#editBranchForm').on('submit', (e) => {
		handle_editBranchForm(e.target);
		return false
	})

	$(document).on('click', '.delete_branch', async (e) => {
	    let id = $(e.currentTarget).data('recid');
	    swal({
	        title: "Are you sure?",
	        text: `You are going to delete this ${branch_keyword.sing} record.`,
	        icon: "warning",
	        className: 'warning-swal',
	        buttons: ["Cancel", "Yes, delete"],
	    }).then(async (confirm) => {
	        if (confirm) {
	            let data = { id: id };
	            try {
	                let response = await send_orgPost('delete branch', data);

	                if (response) {
	                    let res = JSON.parse(response);
	                    if (res.error) {
	                        toaster.warning(res.msg, 'Sorry', { top: '30%', right: '20px', hide: true, duration: 5000 });
	                    } else {
	                        toaster.success(res.msg, 'Success', { top: '20%', right: '20px', hide: true, duration: 2000 }).then(() => {
	                            // location.reload();
	                            load_braches();
	                        });
	                        console.log(res);
	                    }
	                } else {
	                    console.log('Failed to edit branch.' + response);
	                }

	            } catch (err) {
	                console.error('Error occurred during form submission:', err);
	            }
	        }
	    });
	});
	
});