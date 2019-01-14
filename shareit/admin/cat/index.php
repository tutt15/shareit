<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/header.php'; ?>
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/leftbar.php'; ?>

<script>
    $(document).ready(function()
    {
        $('#search_text').keyup(function()
        {
            var txt = $('#search_text').val();
            $.ajax({  
            url:"/admin/cat/search.php",  
            method:"POST",  
            data:{search:txt},  
            success:function(data)  
            {  
            $('#content-table').html(data);  
            }  
            });  
        });
    });
</script>
<script>  
    $(document).ready(function(){  
        load_data();  
        function load_data(page)  
        {  
            var searchtext = $('#search_text').val();  
            $.ajax({  
                    url:"/admin/cat/search.php",  
                    method:"POST",  
                    data:{page:page, search:searchtext},  
                    success:function(data){  
                        $('#content-table').html(data);  
                    }  
            })  
        }  
        $(document).on('click', '.pagination_link', function(){  
            var page = $(this).attr("id");  
            load_data(page);  
        });  
    });  
</script> 
<div id="page-wrapper">
    <div id="page-inner">
        <div class="row">
            <div class="col-md-12">
                <h2>Quản lý danh mục</h2>
            </div>
        </div>
        <!-- /. ROW  -->

        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-sm-1">
                                    <?php
                                        if($_SESSION['userInfo']['role'] == 'admin'){
                                    ?>
                                    <a href="add.php" class="btn btn-success btn-md"><i class="fa fa-plus"></i> Thêm</a>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="col-sm-4">
                                    <?php
                                        if(isset($_SESSION['success'])){
                                    ?>
                                    <p class="alert alert-success text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']) ?></p>
                                    <?php
                                        }
                                    ?> 
                                    <?php
                                        if(isset($_SESSION['error'])){
                                    ?>
                                    <p class="alert alert-danger text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']) ?></p>
                                    <?php
                                        }
                                    ?>
                                </div>
                                <div class="col-sm-7" style="text-align: right;">                               
                                    <input type="search" id="search_text" class="form-control input-sm" placeholder="Tên danh mục" style="float:right; width: 300px;" />
                                    <div style="clear:both"></div><br />
                                </div>
                            </div>

                            <div id="content-table"><!-- content-table -->
                                <!-- data in search.php -->
                            </div><!-- /content-table -->                
                            
                        </div>

                    </div>
                </div>
                <!--End Advanced Tables -->
            </div>
        </div>
    </div>

</div>
<!-- /. PAGE INNER  -->
<?php require_once $_SERVER['DOCUMENT_ROOT'].'/templates/admin/inc/footer.php'; ?>