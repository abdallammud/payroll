<div class="page content">
	<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
        <h5 class="">Add New Employee</h5>
        <div class="ms-auto d-sm-flex">
            <div class="btn-group smr-10">
                <a href="<?=baseUri();?>/employees"  class="btn btn-secondary">Go Back</a>
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
			<form class="modal-content" id="addEmployeeForm" style="border-radius: 14px 14px 0px 0px; margin-top: -15px;">
        	
            <div class="modal-body">
                <div id="">
                	<p class="bold smt-10">Employee Information</p>
                    <div class="row">
                        <div class="col col-xs-12 col-md-6 col-lg-5">
                            <div class="form-group">
                                <label class="label required" for="employeeName">Employee Name</label>
                                <input type="text"  class="form-control " id="employeeName" name="employeeName">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="employeePhone">Phone Number</label>
                                <input type="text"  class="form-control " id="employeePhone" name="employeePhone">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="label required" for="employeeEmail">Email</label>
                                <input type="text"  class="form-control " id="employeeEmail" name="employeeEmail">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="nationalID">ID Number</label>
                                <input type="text" placeholder="National ID"  class="form-control " id="nationalID" name="nationalID">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="gender">Gender Number</label>
                                <select  class="form-control " id="gender" name="gender">
                                	<option value="">- Select</option>
                                	<option value="Male">Male</option>
                                	<option value="Female">Female</option>
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="dob">Date Of Birth</label>
                                <input type="text"  class="form-control cursor datepicker" readonly id="dob" value="<?php echo date('Y-m-d', strtotime("-18 years")); ?>" name="dob">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="address">Address</label>
                                <input type="text"  class="form-control " id="address" name="address">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-xs-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="label required" for="country">Country</label>
                                <select  name="country" class="form-control" id="country">
                                	<option value="">- Select </option>
                                	<?php 
									$countries = $GLOBALS['countryClass']->read_all();

									foreach ($countries as $country) {
										echo '<option value="'.$country['country_id'].'"';
										if($country['is_default'] == 'Yes') echo 'selected="selected"';
										echo '>'.$country['country_name'].'</option>';
									}

                                	?>
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="state">State</label>
                                <input type="text"  class="form-control " id="state" name="state">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="city">City</label>
                                <input type="text"  class="form-control " id="city" name="city">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="label required" for="postal-code">Postal Code</label>
                                <input type="text"  class="form-control "  id="postal-code" name="postal-code">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                    </div>

                    <p class="bold smt-20" style="margin-bottom: 0px;">Contract Information</p>
                    <div class="row">
                    	<div class="col col-xs-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="label required" for="jobTitle">Job Title</label>
                                <input type="text"  class="form-control " id="jobTitle" name="jobTitle">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="label required" for="employeeDep"><?=$GLOBALS['branch_keyword']['sing'];?></label>
                                <select  name="employeeDep" class="form-control" id="employeeDep">
                                	<option value="">- Select <?=$GLOBALS['branch_keyword']['sing'];?></option>
                                	<?php 
                                	$sql = "SELECT * FROM `branches` WHERE `status` = ?";
									$params = ['active'];  
									$types = 's'; 
									$activeBranches = $GLOBALS['branchClass']->query($sql, $params, $types);

									foreach ($activeBranches as $branch) {
										echo '<option value="'.$branch['id'].'">'.$branch['name'].'</option>';
									}

                                	?>
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="label required" for="JobType">Job Type</label>
                                <select  name="JobType" class="form-control" id="JobType">
                                	<option value="">- Select </option>
                                	<option value="Full-time">Full-time</option>
                                	<option value="Part-time">Part-time</option>
                                	<option value="Contract">Contract</option>
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="label required" for="hireDate">Hire Date</label>
                                <input type="text"  class="form-control cursor datepicker" readonly value="<?php echo date('Y-m-d'); ?>" id="hireDate" name="hireDate">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        
                        
                    </div>

                    <div class="row">
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="employeeSalary">Base Salary</label>
                                <input type="text" class="form-control " id="employeeSalary" onkeypress="return isNumberKey(event)" name="employeeSalary">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="bonus">Bonus</label>
                                <input type="text" class="form-control " id="bonus" onkeypress="return isNumberKey(event)" name="bonus">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="contractStart">Effective From</label>
                                <input type="text" class="form-control cursor datepicker" readonly value="<?php echo date('Y-m-d'); ?>" id="contractStart" name="contractStart">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="contractEnd">Effective Untill</label>
                                <input type="text" class="form-control cursor datepicker" readonly value="<?php echo date('Y-m-d', strtotime('+1 year')); ?>" id="contractEnd" name="contractEnd">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                    </div>

                    <p class="bold smt-20" style="margin-bottom: 0px;">Education Information</p>
                    <div class="row education-row">
                        <div class="col col-xs-12 col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="label required" for="degree">Degree</label>
                                <input type="text"  class="form-control degree" id="degree" name="degree">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-3">
                            <div class="form-group">
                                <label class="label required" for="institution">Institution</label>
                                <input type="text"  class="form-control institution" id="institution" name="institution">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="label required" onkeypress="return isNumberKey(event)" for="startYear">Started</label>
                                <input type="text"  class="form-control startYear" id="startYear" name="startYear">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-2">
                            <div class="form-group">
                                <label class="label required" for="endYear">Graduated</label>
                                <input type="text" onkeypress="return isNumberKey(event)"  class="form-control endYear" id="endYear" name="endYear">
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        <div class="col col-xs-12 col-md-6 col-lg-1">
                            <div class="form-group">
                                <label class="label required">&nbsp;</label>
                                <button type="button" class="btn form-control add-educationRow btn-info cursor" style="color: #fff;" >
                                	<i class="fa fa-plus-square"></i>
                                </button>
                                <!-- <button type="button" class="btn form-control remove-educationRow btn-danger cursor" style="display: none;">
                                	<i class="fa fa-trash"></i>
                                </button> -->
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                    	<div class="col-sm-12 justify-content-end d-flex">
                    		<a href="<?=baseUri();?>/employees" class="btn smr-10 btn-secondary cursor" style="width: 100px;">Cancel</a>
                			<button type="submit" class="btn btn-primary cursor" style="width: 100px;">Save</button>
                    	</div>
                    </div>
                </div>
            </div>

            
        </form>
		</div>
	</div>

				
</div>


<?php 
// require('org_edit.php');
?>
