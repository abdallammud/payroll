<div class="page content">
	<div class="page-breadcrumb d-sm-flex align-items-center mb-3">
        <h5 class=""><?=$GLOBALS['branch_keyword']['plu'];?></h5>
        <div class="ms-auto d-sm-flex">
            <div class="btn-group smr-10">
                <button type="button" data-bs-toggle="modal" data-bs-target="#add_branch"  class="btn btn-primary">Add <?=$GLOBALS['branch_keyword']['sing'];?></button>
            </div>
            <div class="ms-auto d-none d-md-block">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-primary">Menu</button>
                    <button type="button" class="btn btn-outline-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">	<span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                    	<a class="dropdown-item" href="<?=baseUri();?>/org">Organization</a>
                        <a class="dropdown-item" href="<?=baseUri();?>/<?=strtolower($GLOBALS['branch_keyword']['plu']);?>"><?=$GLOBALS['branch_keyword']['plu'];?></a>
                        <a class="dropdown-item" href="<?=baseUri();?>/chart"> Chart</a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card">
		<div class="card-body">
			<div class="table-responsive">
				<table id="branchesDT" class="table table-striped table-bordered" style="width:100%">
					
				</table> 
			</div>
		</div>
	</div>

				
</div>


<?php 
require('branch_add.php');
require('branch_edit.php');
?>
