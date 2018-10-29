<?php $this->load->view("partial/header"); ?>


<div id="title_bar" class="print_hide btn-toolbar">
	<span style='font-weight: bold; font-size: 30px; margin-left: 10px;color: #217DBB;
    font-family: "Times New Roman",serif;'>Lịch sử logs</span>
</div>

<div id="toolbar">
	<div class="pull-left form-inline" role="toolbar">
		<button id="delete" class="btn btn-default btn-sm print_hide">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>
		<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control 
		input-sm'
		, 'id'=>'daterangepicker')); ?>

		<!-- TIm theo nguoi -->
		<span style="margin-left:10px;"></span>
		<?php echo form_dropdown('people_manager',$allpeople, "", array('id'=>'people_manager', 'class'=>'form-control')); ?>

		<span style="margin-left:10px;"></span>
		<!-- Tim theo loai hanh dong -->
		<select name="persion_type" id="persion_type" class="form-control input-sm">
			<option value="">-- Chọn hành động --</option>
			<option value="login">Đăng nhập</option>
			<option value="edit"">Sửa</option>
			<option value="delete">Xóa</option>
		</select>
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<div id="payment_summary">
</div>
<script type="text/javascript">
$(document).ready(function()
{

	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
		table_support.refresh();
	});

	 $("#people_manager").change(function() {
        table_support.refresh();
    });

	 $("#persion_type").change(function() {
        table_support.refresh();
    });
	

	// load the preset datarange picker
	<?php $this->load->view('partial/daterangepicker'); ?>

	$('#daterangepicker').data('daterangepicker').setStartDate("<?php echo date($this->config->item('dateformat'), mktime(0,0,0,date("m"),01,date("Y")));?>");
	// update the hidden inputs with the selected dates before submitting the search data
	var start_date = "<?php echo date('Y-m-d', mktime(0,0,0,date("m"),01,date("Y")));?>";
	$("#daterangepicker").on('apply.daterangepicker', function(ev, picker) {
        table_support.refresh();
    });

	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: 'logs',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'id',
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				$("#payment_summary").html(response.payment_summary);
				$("#table tbody tr:last td:first").html("");
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], {
				start_date: start_date,
				end_date: end_date,
				people_manager: $('#people_manager').val(),
				persion_type: $('#persion_type').val(),
				filters: $("#filters").val() || [""]
			});
		},
		columns: {
			'invoice': {
				align: 'center'
			}
		}
	});

});
</script>
<?php $this->load->view("partial/footer"); ?>
