<div class="row">
    <div class="col-md-12 col-lg-4">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">Curencies</h5>
		        <div class="ms-auto d-sm-flex">
		            <div class="btn-group smr-10">
		                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add Currency</button>
		            </div>
		        </div>
		    </div>
		    <hr>
		    
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table id="locationsDT" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>Currency</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>SOS</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>USD</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>EUR</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								
							</tbody>
						</table> 
					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="col-md-12 col-lg-8">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">Exchange rates</h5>
		        <div class="ms-auto d-sm-flex">
		            <div class="btn-group smr-10">
		                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add Rate</button>
		            </div>
		        </div>
		    </div>
		    <hr>
		    
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table id="statesDT" class="table table-striped table-bordered" style="width:100%">
							<thead>
								<tr>
									<th>1 Unit Currency</th>
									<th>Converted to</th>
									<th>Exchange rate</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>USD</td>
									<td>SOS</td>
									<td>27,000</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								
							</tbody>
						</table> 
					</div>
				</div>
			</div>
		</div>
    </div>
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function() {
		var datatable = $('#statesDT').DataTable({searching: false,  info: false});
		var datatable = $('#locationsDT').DataTable({searching: false,  info: false});
	});
	
</script>

<?php 
require('branch_add.php');
require('branch_edit.php');
?>
