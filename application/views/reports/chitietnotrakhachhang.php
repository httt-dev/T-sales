<?php $this->load->view("partial/header"); ?>
<div id="title_bar" class="btn-toolbar">
  <span style='font-weight: bold; font-size: 25px; margin-left: 10px;color: #217DBB;
    font-family: "Times New Roman",serif;'>Danh sách chi tiết hàng nợ trả sản phẩm <?php echo $datas[0]['item_number'] ?> của khách hàng <?php echo $datas[0]['full_name'] ?></span>
</div>
<div id="table_holder">
        <table class="table table-hover table-striped">
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
                      $tonggui = $tongtra = 0;
					//echo "<pre>";print_r($datas); die;
                        foreach($datas as $data){
                          $tonggui = $tonggui + $data['quantity_loan'];
                          $tongtra = $tongtra + $data['quantity_loan_return'];
                        ?>
                            <tr>
                                <td><?php echo date("d-m-Y H:i:s", strtotime($data['sale_time'])) ?></td>
								<td><?php echo $data['sale_price'] ?></td>
                                <td><?php echo $data['quantity_loan']; ?></td>
								<td><?php echo $data['quantity_loan_return']; ?></td>
                                <td><?php echo get_link_by_type($data['type'],$data['sale_id']); ?>
                                </td>
                            </tr>
                        <?php    
                        }
                    ?>
                    <tr>
                       <td colspan="2">Tổng</td>
                       <td><?php echo $tonggui?></td>
                       <td><?php echo $tongtra?></td>
                       <td>Nợ: <?php echo $tongtra - $tonggui?></td>
                    </tr>
                </tbody>
            </table>
    </div>
</div>
<?php $this->load->view("partial/footer"); ?>