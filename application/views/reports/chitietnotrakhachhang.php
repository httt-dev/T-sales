<div class="card">
    <div role="tabpanel" class="tab-pane active" id="da_chon">
		<h1>Danh sách chi tiết hàng nợ trả sản phẩm <?php echo $datas[0]['item_number'] ?></h1>
		<h2>Khách hàng: <?php echo $datas[0]['full_name'] ?></h2>
        <table class="dachon_sales_table" class="table table-striped table-hover search_table">
                <thead>
                    <tr bgcolor="#CCC">
                        <th>Ngày bán hàng</th>
                        <th>Trị giá hóa đơn</th>
                        <th>Số lượng gửi</th>
                        <th>Số lượng trả</th>
                        <th>Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					//echo "<pre>";print_r($datas); die;
                        foreach($datas as $data){
                        ?>
                            <tr>
                                <td><?php echo date("d-m-Y H:i:s", strtotime($data['sale_time'])) ?></td>
								<td><?php echo $data['sale_price'] ?></td>
                                <td><?php echo $data['quantity_loan']; ?></td>
								<td><?php echo $data['quantity_loan']; ?></td>
                                <td><?php echo get_link_by_type($data['type'],$data['sale_id']); ?>
                                </td>
                            </tr>
                        <?php    
                        }
                    ?>
                </tbody>
            </table>
    </div>
</div>
<style>
.dachon_sales_table {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.dachon_sales_table td, .dachon_sales_table th {
  border: 1px solid #ddd;
  padding: 8px;
}

.dachon_sales_table tr:nth-child(even){background-color: #f2f2f2;}

.dachon_sales_table tr:hover {background-color: #ddd;}

.dachon_sales_table th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>