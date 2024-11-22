<div class="row">
    <div class="col-md-12 col-lg-12">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">Bank Accounts</h5>
		        <div class="ms-auto d-sm-flex">
		            <div class="btn-group smr-10">
		                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add Bank Account</button>
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
									<th>Bank Name</th>
									<th>Account Number</th>
									<th>Current Balance</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Dahabshiil</td>
									<td>123456789</td>
									<td>$123,412.22</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Salaam Somalia Bank</td>
									<td>123456789</td>
									<td>$123,412.22</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Primier Bank</td>
									<td>123456789</td>
									<td>$123,412.22</td>
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
		var datatable = $('#statesDT').DataTable({info: false});
		var datatable = $('#locationsDT').DataTable({ info: false});
	});
	
</script>

<?php 
require('branch_add.php');
require('branch_edit.php');
?>
