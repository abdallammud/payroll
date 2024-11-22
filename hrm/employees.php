<div class="page content">
	<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
        <h5 class="">Employees</h5>
        <div class="ms-auto d-sm-flex">
            <div class="btn-group smr-10">
                <a href="<?=baseUri();?>/employees/add"  class="btn btn-primary">Add Employee</a>
            </div>
            <div class="ms-auto d-none d-md-block">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary">Menu</button>
                    <button type="button" class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    	<?php require('hrm_menu.php'); ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <hr>
    <div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="employeesDT" class="table table-striped table-bordered" style="width:100%">
					
				</table> 
			</div>
		</div>
	</div>
</div>

