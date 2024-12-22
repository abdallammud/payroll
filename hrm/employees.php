<div class="page content">
	<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
        <h5 class="">Employees</h5>
        <div class="ms-auto d-sm-flex">
        	<div class="btn-group smr-10">
	            <button type="button" data-bs-toggle="modal" data-bs-target="#upload_employees"  class="btn btn-primary">Upload employees</button>
	        </div>
            <div class="btn-group smr-10">
                <a href="<?=baseUri();?>/employees/add"  class="btn btn-primary">Add Employee</a>
            </div>
            
        </div>
    </div>
    <hr>
    <div class="card">
		<div class="card-body">
			<div class="row d-md-none d-lg-flex">
                <div class="col col-xs-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label class="label required" for="slcDepartment">Department</label>
                        <select  class="form-control filter " id="slcDepartment" name="slcDepartment">
                        	<option value="">All</option>
                            <?php 
                        	select_active('branches');
                        	?>
                        </select>
                        <span class="form-error text-danger">This is error</span>
                    </div>
                </div>
                <div class="col col-xs-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label class="label required" for="slcState">State</label>
                        <select  class="form-control filter " id="slcState" name="slcState">
                        	<option value="">All</option>
                            <?php 
							select_active('states');
                        	?>
                        </select>
                        <span class="form-error text-danger">This is error</span>
                    </div>
                </div>
                <div class="col col-xs-12 col-md-6 col-lg-3">
                    <div class="form-group">
                        <label class="label required" for="slcLocation">Duty Location</label>
                        <select  class="form-control filter " id="slcLocation" name="slcLocation">
                        	<option value="">All</option>
                            <?php 
                            select_active('locations');
                            ?>
                        </select>
                        <span class="form-error text-danger">This is error</span>
                    </div>
                </div>
                <div class="col col-xs-12 col-md-6 col-lg-2">
                    <div class="form-group">
                        <label class="label required" for="slcStatus">Status</label>
                        <select  class="form-control filter " id="slcStatus" name="slcStatus">
                        	<option value="">All</option>
                            <option value="Active">Active</option>
                            <option  value="Suspended">Suspended</option>
                            <option  value="Deleted">Deleted</option>
                        </select>
                        <span class="form-error text-danger">This is error</span>
                    </div>
                </div>
			</div>

			<div class="table-responsive">
				<table id="employeesDT" class="table table-striped table-bordered" style="width:100%">
					
				</table> 
			</div>
		</div>
	</div>
</div>

<?php require('employees_upload.php'); ?>