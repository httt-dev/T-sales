<?php $this->load->view("partial/header"); ?>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}
?>

<div class="row">

	<div class="col-md-6">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt">&nbsp</span>Báo cáo công nợ</h3>
		  	</div>
			<div class="list-group">
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/congnothu');?>">01 - Công nợ phải thu</a>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/congnotra');?>">02 - Công nợ phải trả</a>
			 </div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt">&nbsp</span>Báo cáo doanh số</h3>
		  	</div>
			<div class="list-group">
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/doanhsosanluong');?>">03 - Doanh số theo sản lượng</a>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/doanhsokhachhang');?>">04 - Doanh số theo khách hàng</a>
			 </div>
		</div>
	</div>
</div>

<div class="row">
	

	<div class="col-md-6">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list-alt">&nbsp</span>Kết quả kinh doanh</h3>
		  	</div>
			<div class="list-group">
				<?php if($checkadmin) {?>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/ketquakinhdoanh');?>">05 - Kết quả kinh doanh</a>
				<?php }	 ?>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/soquytienmat');?>">06 - Sổ quỹ tiền mặt</a>
			 </div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="panel panel-primary">
		  	<div class="panel-heading">
				<h3 class="panel-title"><span class="glyphicon glyphicon-list">&nbsp</span><?php echo $this->lang->line('reports_summary_reports'); ?></h3>
		  	</div>
			<div class="list-group">
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hanghoanhapkho');?>">07 - Hàng hóa nhập kho</a>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hanghoaxuatkho');?>">08 - Hàng hóa xuất kho</a>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hanghoatonkho');?>">09 - Hàng hóa tồn kho</a>
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hoanghoaxuatkhobaobi');?>">10 - Hàng hóa xuất kho bao bì</a>
				<!--<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hoanghoatonkhobaobi');?>">11 - Hàng hóa tồn kho bao bì</a>-->
				<a class="list-group-item" href="<?php echo site_url('reports/detailed_sales/hanghoanotra');?>">11 - Báo cáo hàng hóa nợ trả</a>
			 </div>
		</div>
	</div>
</div>

<div class="row">
	<div id="wrap-btn-backup" class="col-md-6">
		<button type="button" class="btn btn-success" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing Order">Sao lưu</button>
		<span>(Hệ thống sẽ tự động gửi dữ liệu vào mail cá nhân)</span>
	</div>
	<div style="display: none" id="loader">
		<div class="loader"></div>
		<span>Hệ thống đang backup và gửi Email...</span>
	</div>
</div>
<style>
.loader {
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
<?php $this->load->view("partial/footer"); ?>

<script>
$(document).ready(function()
{
	$('#load').on('click', function() {
		var r = confirm("Bạn có chắc chắn muốn backup và gửi email!");
		if (r == true) {
		    $("#wrap-btn-backup").hide();
			$("#loader").show();
			$.ajax({
		        url : '<?php echo site_url('mailbackup/index.php');?>',
		        type : "post",
		        dataType:"json",
		        data : {
		        	token: 'yRQYnWzskCZUxPwaQupWkiUzKELZ49eM7oWxAQK_ZXw',
		        	name: '<?php echo $full_name;?>'
		        },
		        success : function (arrResult){
		        	$("#wrap-btn-backup").show();
					$("#loader").hide();
					alert(arrResult['message']);
		        }
		    });
		}
	});
});
</script>