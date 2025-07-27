<?php 
$payroll_id = $_GET['payroll_id'] ?? 0;
$payrollInfo = get_data('payroll', ['id' => $payroll_id]);

$workflow = $myWorkflowStatus = [];
if($payrollInfo) {
	$payrollInfo = $payrollInfo[0];
	$workflow = json_decode($payrollInfo['workflow'], true);
	// my workflow == workflow where user_id == $_SESSION['user_id']	
	foreach ($workflow as $key => $value) {
		if($value['user_id'] == $_SESSION['user_id']) {
			$myWorkflow = $value;
			$myWorkflowStatus[] = $value['status'];
		}
	}
} else {
	$payrollInfo['month'] = '';
	$payrollInfo['ref'] = '';
	$payrollInfo['ref_name'] = '';
	$payrollInfo['added_date'] = '';
	$payrollInfo['status'] = '';
}
// var_dump($myWorkflowStatus);

$payrollInfo['month'] = explode(",", $payrollInfo['month']);

?>
<div class="row">
    <div class="col-md-12 col-lg-12">
		<div class="page content">
			<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
		        <h5 class="">Payroll details </h5>
		        <div class="ms-auto d-sm-flex">
		        	<div class="btn-group smr-10">
		                <a id="download_payroll"  class="btn btn-success"> Download Excel</a>
		            </div>

		            <div class="btn-group smr-10">
		                <a href="<?=baseUri();?>/payroll"  class="btn btn-secondary"> Back</a>
		            </div>  

					
		        </div>
				
		    </div>

		    <hr>
		    
			<div class="card">
				<div class="card-body">
					<div class="row">
						<div class="col-ms-12 col-md-6 col-lg-3">
							<div class="form-group">
                                <label class="label required" for="">Month</label>
                                <select id="payrollMonth" type="text" readonly class="form-control cursor ">
                                	<?php 

                                	if(isset($payrollInfo['month']) && is_array($payrollInfo['month'])) {

                                		foreach ($payrollInfo['month'] as $month ) {
                                			echo '<option value="'.date('Y-m', strtotime($month)).'"> '.date('F Y', strtotime($month)).'</option>';
                                		}
                                	}


                                	 ?>
                                	
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
						</div>
						<div class="col-ms-12 col-md-6 col-lg-3">
							<div class="form-group">
                                <label class="label required" for="payrollMonth">Reference</label>
                                <input type="text" readonly class="form-control cursor " value="<?=$payrollInfo['ref'];?>,  <?=$payrollInfo['ref_name'];?>">
                                <span class="form-error text-danger">This is error</span>
                            </div>
						</div>
						<div class="col-ms-12 col-md-6 col-lg-2">
							<div class="form-group">
                                <label class="label required" for="payrollMonth">Status</label>
                                <input type="text" readonly class="form-control cursor " value="<?=$payrollInfo['status'];?>">
                                <span class="form-error text-danger">This is error</span>
                            </div>
						</div>
						<div class="col-ms-12 col-md-6 col-lg-2">
							<div class="form-group">
                                <label class="label required" for="">Date added</label>
                                <input type="text" readonly class="form-control cursor " value="<?=date('F d, Y', strtotime($payrollInfo['added_date']));?>">
                                <span class="form-error text-danger">This is error</span>
                            </div>
						</div>
						<div class="col-ms-12 col-md-6 col-lg-2">
							<div class="form-group">
                                <label class="label required" for="payrollMonth">&nbsp; </label>
	                            <div class="ms-auto d-sm-flex">
						            <!-- <div class="btn-group " style="width:100%">
						            	<?php if($payrollInfo['status'] == 'Request') { ?>
						                	<button  type="button" data-recid="<?=$payroll_id;?>" class="btn btn-primary approve_payrollBtn">Approve payroll</button>
						                <?php } else if($payrollInfo['status'] == 'Approved') {  ?>
						                	<button  type="button" data-recid="<?=$payroll_id;?>" class="btn btn-primary pay_payrollBtn">Pay payroll</button>
						                <?php } else { ?>
						                	<button  type="button" class="btn btn-success ">Paid</button>
						                <?php } ?>
						            </div> -->

									<div class="btn-group" style="width:100%; height: 41px;">
										<button type="button" style="width:100%" class="btn btn-outline-secondary">Menu</button>
										<button type="button" class="btn btn-outline-secondary split-bg-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
										</button>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
											<?php 
											if(check_session('review_payroll')) {
												if(!in_array('Reviewed', $myWorkflowStatus)) {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Reviewed" data-recid="'.$payroll_id.'" >Review payroll</a>';
												} else if(!in_array('Approved', $myWorkflowStatus) && !in_array('Paid', $myWorkflowStatus)) {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Request" 
													data-undo="Reviewed"
													data-recid="'.$payroll_id.'" >Undo review</a>';
												}
											}
											if(check_session('approve_payroll')) {
												if(!in_array('Approved', $myWorkflowStatus)) {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Approved" data-recid="'.$payroll_id.'" >Approve payroll</a>';
												} else if(!in_array('Paid', $myWorkflowStatus)) {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Reviewed"
													data-undo="Approved"
													data-recid="'.$payroll_id.'" >Undo approval</a>';
												}
											}
											if(check_session('pay_payroll')) {
												if(!in_array('Paid', $myWorkflowStatus)) {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Paid" data-recid="'.$payroll_id.'" >Pay payroll</a>';
												} else  {
													echo '<a class="dropdown-item cursor changeBtn_status" data-action="Approved"
													data-undo="Paid"
													data-recid="'.$payroll_id.'" >Undo payment</a>';
												}
											}
											?>
										</div>
									</div>
						        </div>
                            </div>
						</div>
					</div>
					<div class="table-responsive">
						<table id="showpayrollDT" class="table table-striped table-bordered" style="width:100%">
							<label class="smt-20 btn btn-secondary edit-table_customize" data-table="showpayrollDT">
								<i class="fa smr-5 fa-pencil"> </i>
								Edit table
							</label>

							<label class="smt-20 sml-10 btn btn-primary" data-bs-toggle="modal" data-bs-target="#workflowModal">
								View Workflow
							</label>
							
						</table> 
					</div>
				</div>
			</div>
		</div>
	</div>


</div>
<?php 
$columns = get_columns('showpayrollDT', 'show_columns');
require('./customize_table.php');
?>
<script type="text/javascript">
	var payroll_id = '<?=$payroll_id;?>';
	var	tableColumns = <?=json_encode($columns);?>;
	console.log(tableColumns)
	addEventListener("DOMContentLoaded", (event) => {
		var month = $('#payrollMonth').val();
		if(month) {
			load_showPayroll(payroll_id, month);
			$('#download_payroll').attr('href', `${base_url}/download_payroll.php?id=${payroll_id}&month=${month}`)
		}
		$('#payrollMonth').on('change', () => {
			var month = $('#payrollMonth').val();
			if(month) {
				load_showPayroll(payroll_id, month);
				$('#download_payroll').attr('href', `${base_url}/download_payroll.php?id=${payroll_id}&month=${month}`)
			}
		})
	});

	
</script>

<style type="text/css">
	.dropdown.bootstrap-select.my-select {
		display: block;
		width: 100% !important;
	}
	button.btn.dropdown-toggle:not(.actions) {
		height:auto;
		line-height: 1.5;
		border: 1px solid var(--bs-body-color);
	}
</style>

<!-- Workflow modal -->
<div class="modal fade" id="workflowModal" tabindex="-1" aria-labelledby="workflowModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="min-width:700px; width: 50vw; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="workflowModalLabel">Workflow</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="workflowData">
					<table class="table table-striped table-bordered smt-10">
						<thead>
							<tr>
								<th style="width: 70%">Action</th>
								<th style="width: 30%">Date</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							if(isset($workflow) && !empty($workflow)) {
								foreach ($workflow as $workflowRow ) {
									// Show action and date only
									echo '<tr><td>'.$workflowRow['action'].'</td><td>'.date('F d, Y h:i A', strtotime($workflowRow['date'])).'</td></tr>';
								}
							}
							?>
						</tbody>
					</table>
				</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php 
require('payslip_show.php');
require('pay_payroll.php');
?>
