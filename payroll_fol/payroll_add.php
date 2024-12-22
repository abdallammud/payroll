<div class="modal  fade"   data-bs-focus="false" id="generate_payroll" tabindex="-1" role="dialog" aria-labelledby="generatePayrollLabel" aria-hidden="true">
    <div class="modal-dialog" role="transaction" style="width:500px;">
        <form class="modal-content" id="generatePayrollForm" style="border-radius: 14px 14px 0px 0px; margin-top: -15px;">
            <div class="modal-header">
                <h5 class="modal-title">Add payroll</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <div class="col col-xs-12">
                            <div class="form-group">
                                <label class="label required" for="slcTransFor">Payroll for</label>
                                <select type="text"  class="form-control validate slcTransFor" data-msg="Please select transaction for" name="slcTransFor" id="slcTransFor">
                                    <option value="All"> All</option>
                                    <option value="Department"> Department</option>
                                    <option value="Location"> Duty Location</option>
                                </select>
                                <span class="form-error text-danger">This is error</span>
                            </div>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col col-xs-12">
                            <div class="form-group attenForDiv">
                               
                            </select>
                            </div>
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col col-xs-12">
                            <div class="form-group">
                                <label class="label required" for="payrollMonth">Month</label>
                                <input type="month" class="form-control cursor validate" data-msg="Please select month" id="payrollMonth"  name="payrollMonth">
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
