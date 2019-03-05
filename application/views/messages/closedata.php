
<?php $this->load->view("partial/header"); ?>
<style>
.topbar , .navbar  {
    display:none;
}
</style>
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

li {
    padding:5px;
}
</style>
<div class="panel panel-default">
  <div class="panel-heading">BƯỚC 1: BACKUP DỮ LIỆU</div>
  <div class="panel-body">
    Vào phpmyadmin (quản trị CSDL) Sao lưu database hiện tại (Xuất dữ liệu)
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">BƯỚC 2: TẠO DATABASE LƯU TRỮ</div>
  <div class="panel-body">
    <ul style="margin-left:10px;">
        <li>Tạo Database cũ theo năm vd: db_tsales_2018</li>
        <li>Khôi phục Database đã backup ở bước 1 (Nhập dữ liệu)</li>
        <li>Vào trang db-tsales-old cấu hình lại application\config\database.php tên [database] = database đã tạo ở bước 2</li>
    </ul>
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">BƯỚC 3: ĐỒNG BỘ DỮ LIỆU TỪ PHẦN MỀM CŨ SANG</div>
  <div class="panel-body">
    <form>
        <div class="form-group row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Nhập tên database</label>
            <div class="col-sm-4">
                <input value="db_tsales_" type="text" class="form-control" id="db_name" placeholder="Tên database cần đồng bộ sang">
            </div>
            <div class="col-sm-5">
            <div style="display: none" id="loader">
                <div class="loader"></div>
                <span>Đang đồng bộ dữ liệu, vui lòng đợi trong ít phút...</span>
            </div>
            <button id="updateNewdb" type="button" class="btn btn-danger">Đồng bộ dữ liệu</button>
            </div>
        </div>
    </form>
  </div>
</div>

<!-- <script>
$(document).ready(function()
{
    $('#updateNewdb').on('click', function() {
        //var r = confirm("Bạn có chắc chắn muốn đồng bộ dữ liệu cũ!");
        var r = true;
        if (r == true) {
		    $("#wrap-btn-backup").hide();
			//$("#loader").show();
            //$("#updateNewdb").hide();
			$.ajax({
		        url : "<?php echo site_url('messages/updateNewdb');?>",
		        type : "get",
		        dataType:"json",
		        data : {
		        	token: 'yRQYnWzskCZUxPwaQupWkiUzKELZ49eM7oWxAQK_ZXw',
                    dbName: $("#db_name").val()
		        },
		        success : function (arrResult){
		        	$("#wrap-btn-backup").show();
					$("#loader").hide();
                    $("#updateNewdb").show();
					//alert(arrResult['message']);
		        }
		    });
		}
    });
});
</script> -->