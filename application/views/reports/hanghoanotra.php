<?php $this->load->view("partial/header"); ?>

<div id="page_title"><span style="font-weight: bold; font-size: 25px; margin-left: 10px;color: #217DBB; font-family: "Times New Roman",serif;">Báo cáo hàng nợ trả</span></div>

<?php
if(isset($error))
{
	echo "<div class='alert alert-dismissible alert-danger'>".$error."</div>";
}
?>
<input style="display: none" id="idcustomer" value="">
<input style="display: none" id="type" value="hangnotra">
<input style="display: none" id="mode" value="receive">
<?php echo form_open('#', array('id'=>'item_form', 'enctype'=>'multipart/form-data', 'class'=>'form-horizontal')); ?>
	<div class="form-group form-group-sm" style="margin-left: 5%;">
		<?php echo form_label($this->lang->line('reports_date_range'), 'report_date_range_label', array('class'=>'control-label col-xs-3')); ?>
		<div class="col-xs-4">
				<?php echo form_input(array('name'=>'daterangepicker', 'class'=>'form-control input-sm', 'id'=>'daterangepicker')); ?>
		</div>
	</div>
	<div class="form-group form-group-sm" style="margin-left: 5%;">
		<?php echo form_label('Chọn sản phẩm', 'customer', array('class'=>'control-label col-xs-3')); ?>
			<div class='col-xs-4'>
			<?php echo form_input(array(
					'name'=>'itemid',
					'id'=>'itemid',
					'value'=>'',
					'class'=>'form-control input-sm')
					); ?>
			</div>
		
	</div>
	<table id="table"></table>
</div>

<div id="payment_summary">
</div>
<script type="text/javascript">
$(document).ready(function()
{
	_setDatepicker($('#date_sale'));
	$('#date_sale').change(function() 
	{
		table_support.refresh();
	});
	$("#button_print_doc").click(function()
    {
		window.print();
    });
	// when any filter is clicked and the dropdown window is closed
	$('#filters').on('hidden.bs.select', function(e) {
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
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'sale_id',
		onLoadSuccess: function(response) {
			if($("#table tbody tr").length > 1) {
				$("#payment_summary").html(response.payment_summary);
				$("#table tbody tr:last td:first").html("");
				$(".pagination-detail").hide();
				$(".pagination").hide();
			}
		},
		queryParams: function() {
			return $.extend(arguments[0], {
				start_date: start_date,
				end_date: end_date,
				customer_id: $("#idcustomer").val(),
				type: $("#type").val(),
				mode: $("#mode").val(),
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
var clear_fields = function()
    {
        if ($(this).val().match("<?php echo $this->lang->line('sales_start_typing_item_name') . '|' . $this->lang->line('sales_start_typing_customer_name'); ?>"))
        {
            $(this).val('');
        }
    };

   $('#item,#customer').click(function()
    {
    	$(this).attr('value','');
    });

    $("#itemid").autocomplete(
    {
		source: '<?php echo site_url("sales/item_search"); ?>',
    	minChars:0,
		autoFocus: false,
		delay:10,
		appendTo: ".modal-content",
		select: function(e, ui) {
			e.preventDefault();
			$('#idcustomer').val(ui.item.value);
			$('#itemid').val(ui.item.label);
			table_support.refresh();
		}
    });

	dialog_support.init("a.modal-dlg, button.modal-dlg");

	$('#supplier').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('receivings_start_typing_supplier_name'); ?>");
    });

	$('#comment').keyup(function() 
	{
		$.post('<?php echo site_url($controller_name."/set_comment");?>', {comment: $('#comment').val()});
	});
	function changemode(){
		$("#mode").val($("select[name='mode']").val());
		table_support.refresh();
	}
</script>
<style type="text/css">
	.ui-icon{
		text-indent: 0px !important;
	}
</style>
<?php $this->load->view("partial/footer"); ?>