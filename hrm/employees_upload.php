<div class="modal fade"   data-bs-focus="false" id="upload_employees" tabindex="-1" role="dialog" aria-labelledby="upload_employeesLabel" aria-hidden="true">
    <div class="modal-dialog" role="upload_employees" style="width:500px;">
        <form class="modal-content" id="upload_employeesForm" style="border-radius: 14px 14px 0px 0px; margin-top: -15px;">
        	<div class="modal-header">
                <h5 class="modal-title">Upload Employees</h5>
                <button type="button" class="close modal-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="row">
                        <label class="cursor col col-xs-12 col-md-12">
                        	<input class="form-control py-2" id="upload_employeesInput"  type="file" name="">
                        	<span class="file-selected-name"></span>
                        </label>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="row mt-3" id="upload_progress" style="display: none;">
                        <div class="col-12">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Text -->
                    <div class="row mt-2" id="upload_progress_text" style="display: none;">
                        <div class="col-12 text-center">
                            <small class="text-muted">Starting upload...</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <a href="<?=baseUri();?>/assets/docs/employee upload sample.csv" download="employee upload sample.csv" class="btn btn-secondary cursor " style="min-width: 100px;">Download sample file.</a>
                <button type="submit" class="btn btn-primary cursor" style="min-width: 100px;">Upload</button>
            </div>
        </form>
    </div>
</div>