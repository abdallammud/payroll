
<div class="page content">
	<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
        <h5 class="">System Roles</h5>
        <div class="ms-auto d-sm-flex">
            <div class="btn-group smr-10">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add_role">Add Role</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="rolesDT" class="table table-striped table-bordered" style="width:100%">
					
				</table> 
			</div>
		</div>
	</div>
</div>

<!-- Add role -->
<div class="modal  fade"  data-bs-focus="false" id="add_role" tabindex="-1" role="dialog" aria-labelledby="add_roleLabel" aria-hidden="true">
    <div class="modal-dialog" role="role" style="width:500px;">
        <form class="modal-content" id="addRoleForm" style="border-radius: 14px 14px 0px 0px; margin-top: -15px;">
        	<div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col col-xs-12">
                            <div class="form-group">
                                <label class="label required" for="roleName">Role Name</label>
                                <input type="text"  class="form-control validate" data-msg="role name is required" id="roleName" name="roleName">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cursor " data-bs-dismiss="modal" aria-label="Close" style="min-width: 100px;">Cancel</button>
                <button type="submit" class="btn btn-primary cursor" style="min-width: 100px;">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit role -->
<div class="modal fade" data-bs-focus="false" id="edit_role" tabindex="-1" role="dialog" aria-labelledby="edit_roleLabel" aria-hidden="true">
    <div class="modal-dialog" role="role" style="width:500px;">
        <form class="modal-content" id="editRoleForm" style="border-radius: 14px 14px 0px 0px; margin-top: -15px;">
            <input type="hidden" id="edit_role_id" name="id">
            <div class="modal-header">
                <h5 class="modal-title">Edit Role</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col col-xs-12">
                            <div class="form-group">
                                <label class="label required" for="edit_roleName">Role Name</label>
                                <input type="text" class="form-control validate" data-msg="role name is required" id="edit_roleName" name="name">
                                <span class="form-error text-danger">This field is required</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cursor" data-bs-dismiss="modal" aria-label="Close" style="min-width: 100px;">Cancel</button>
                <button type="submit" class="btn btn-primary cursor" style="min-width: 100px;">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
// Dome content loaded
document.addEventListener("DOMContentLoaded", function() {

    // Initialize roles DataTable
    var rolesTable = $('#rolesDT').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": base_url + "/app/users_controller.php?action=load&endpoint=roles",
            "type": "POST"
        },
        "columns": [
            { "data": "role", "title": "Role Name" },
            { 
                "data": null, 
                "title": "Actions",
                "orderable": false,
                "render": function(data, type, row) {
                    return `
                        <button class="btn btn-sm btn-primary edit-role" data-id="${row.id}" data-name="${row.role}">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-role" data-id="${row.id}" data-name="${row.role}">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    `;
                }
            }
        ]
    });

    // Handle edit role button click
    $(document).on('click', '.edit-role', function() {
        const roleId = $(this).data('id');
        const roleName = $(this).data('name');
        
        $('#edit_role_id').val(roleId);
        $('#edit_roleName').val(roleName);
        $('#edit_role').modal('show');
    });

   

});
</script>