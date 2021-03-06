<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
$(document).ready(function()
{
	<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

	table_support.init({
		resource: '<?php echo site_url($controller_name);?>',
		headers: <?php echo $table_headers; ?>,
		pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
		uniqueId: 'people.person_id',
		enableActions: function()
		{
			var email_disabled = $("td input:checkbox:checked").parents("tr").find("td a[href^='mailto:']").length == 0;
			$("#email").prop('disabled', email_disabled);
		}
	});

	

	$("#email").click(function(evvent)
	{
		var recipients = $.map($("tr.selected a[href^='mailto:']"), function(element)
		{
			return $(element).attr('href').replace(/^mailto:/, '');
		});
		location.href = "mailto:" + recipients.join(",");
	});

});

</script>

<div id="title_bar" class="btn-toolbar">
	<a style="color:white" href="<?php echo site_url('messages/closedata');?>"><button class='btn btn-danger btn-sm pull-right'>
		<span class="glyphicon glyphicon-eye-open">&nbsp</span>
		Chốt số liệu theo năm
	</button></a>
	<span class="pull-right"> &#160;&#160;</span>
	<button class='btn btn-success btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/config"); ?>'
			title='<?php echo 'Cấu hình hệ thống' ?>'>
		<span class="glyphicon glyphicon-list-alt">&nbsp</span><?php echo 'Thông tin mẫu in'; ?>
	</button>
	<button class='btn btn-info btn-sm pull-right modal-dlg' data-btn-submit='<?php echo $this->lang->line('common_submit') ?>' data-href='<?php echo site_url($controller_name."/view"); ?>'
			title='<?php echo $this->lang->line($controller_name. '_new'); ?>'>
		<span class="glyphicon glyphicon-user">&nbsp</span><?php echo $this->lang->line($controller_name. '_new'); ?>
	</button>
	<a style="color:white" href="<?php echo site_url('employees/logs');?>"><button class='btn btn-warning btn-sm pull-right'>
		<span class="glyphicon glyphicon-eye-open">&nbsp</span>
		Xem lịch sử Log
	</button></a>
</div>

<div id="toolbar">
	<div class="pull-left btn-toolbar">
		<button id="delete" class="btn btn-default btn-sm">
			<span class="glyphicon glyphicon-trash">&nbsp</span><?php echo $this->lang->line("common_delete");?>
		</button>
	</div>
</div>

<div id="table_holder">
	<table id="table"></table>
</div>

<?php $this->load->view("partial/footer"); ?>
