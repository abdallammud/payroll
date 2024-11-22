<div class="row">
    <div class="col-md-12 col-lg-8">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">Duty Locations</h5>
		        <div class="ms-auto d-sm-flex">
		            <div class="btn-group smr-10">
		                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add Location</button>
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
									<th>Location</th>
									<th>City</th>
									<th>State</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>Mogadishu Office</td>
									<td>Mogadishu</td>
									<td>Banadir</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Kismayo Office</td>
									<td>Kismayo</td>
									<td>Jubaland</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Badoa Office</td>
									<td>Badoa</td>
									<td>South West</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Garowe Office</td>
									<td>Garowe</td>
									<td>Puntland</td>
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

    <div class="col-md-12 col-lg-4">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">States</h5>
		        <div class="ms-auto d-sm-flex">
		            <div class="btn-group smr-10">
		                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add State</button>
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
									<th>State</th>
									<th>Tax %</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>South West</td>
									<td>12%</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Galmudug</td>
									<td>12%</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Hiirshabeele</td>
									<td>12%</td>
									<td>
										<i class="fa smr-10 fa-pencil"></i>
										<i class="fa fa-trash-alt"></i>
									</td>
								</tr>
								<tr>
									<td>Puntland</td>
									<td>12%</td>
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
