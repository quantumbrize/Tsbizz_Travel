<?php 
if($_REQUEST['id']!=''){ 
$select1='*';    
$where1='id="'.decode($_REQUEST['id']).'"';  
$rs1=GetPageRecord($select1,'templateMaster',$where1);   
$editresult=mysqli_fetch_array($rs1);  

}
 

 ?>

 <script language="JavaScript" type="text/javascript" src="ckeditor/ckeditor.js"></script> 
<script language="JavaScript" type="text/javascript" src="ckeditor/ckfinder/ckfinder.js"></script>

<style>
.table td, .table th {
    vertical-align: top;
}
label{width: 100% !important; margin-bottom: 2px !important;font-size: 12px; text-transform: uppercase;}
</style>
<div class="wrapper">
<div class="container-fluid">
<div class="main-content">

                <div class="page-content">

      
                    
                    <!-- start page title -->
                     
              
                        <div class=" ">
                        <div class="col-md-12 col-xl-12">
						<div class="card" style="min-height:500px;">
                            <div class="card-body"> 
                                     
									        <div class=" "> 
									
									<form action="frmaction.html" method="post" enctype="multipart/form-data" name="addeditfrm" target="actoinfrm" id="addeditfrm" > 
                    <div class=" ">
                        <div class="col-lg-12">
               
                                 
                                    <div class="row"> 
                                         


										  <div class="col-lg-12">
										  <h4 class="card-title" style=" margin-top:0px; overflow:hidden;"><?php if($_REQUEST['id']!=''){ echo 'Edit'; } else { echo 'Add'; } ?> Email Template<div class="float-right">
									<a href="display.html?ga=mailing-templates" ><button type="button" class="btn btn-primary btn-lg waves-effect waves-light" style="margin-bottom:10px;"  >Back</button></a>
									</div></h4>
										  <div class="row"  style="padding: 5px; margin: 5px; border: 1px solid #ddd; padding-top: 12px; border-radius: 4px;">
										  	 
							  
										 
										<div class="col-lg-6">
											 <div class="form-group"> 
											 <label for="validationCustom02">Template Name</label>
                                          <input type="text" class="form-control redborder" id="name" name="name"    value="<?php echo stripslashes($editresult['name']); ?>" >
                                        </div>
										</div>
											
		 
											<div class="col-lg-6">
											 <div class="form-group"> 
											 <label for="validationCustom02">Email Subject</label>
                                          <input type="text" class="form-control redborder" id="subject" name="subject"    value="<?php echo stripslashes($editresult['subject']); ?>" >
                                        </div>
										</div> 
											 			 
											 
											  
											 
											   
											  
											 
											 <div class="col-lg-12">
											 
											 <div class="form-group">
                                                 <label for="validationCustom02">Email Body</label>
                                                <textarea name="details" class="editorclass" id="details" style="height:350px;"><?php echo stripslashes($editresult['details']); ?><?php echo stripslashes($_REQUEST['details']); ?></textarea>
												
												
												<script type="text/javascript">

var editor = CKEDITOR.replace('details');

CKFinder.setupCKEditor( editor,'ckeditor/ckfinder/' ) ;

</script>
  
                                            </div>
											 
											 </div>
											 
										  </div>		  
                                             
											  <div class="form-group mb-0" style="padding: 20px 20px;  border-top: 1px solid #e6e6e6; overflow:hidden; margin-top:20px;">
                                           
                                   
											
											 <button type="submit"  id="savingbutton" class="btn btn-secondary" onclick="this.form.submit(); this.disabled=true; this.value='Saving...';"  style="float:right;"  >
                                                Save Template
                                            </button>
                                             <input autocomplete="false" name="action" type="hidden" id="action" value="addemailtemplate" /> 
											 <input autocomplete="false" name="editid" type="hidden" id="editid" value="<?php echo $_REQUEST['id']; ?>" /> 
								</div>
											 
										 
											                              
                                        </div>

                                         
                                    </div>  
									
									 
						
						
						
                    </div>
					
					</form>
					 
						  </div>		  </div>
								 
                             
</div>
                             

                        </div>

                         
						
						
						
						 
                     

             </div><!--end col-->

            <!-- end row -->

    </div>

        <!-- End Page-content -->

         
    </div>
	</div>	</div>