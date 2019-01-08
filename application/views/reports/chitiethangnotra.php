<div class="card">
    <div role="tabpanel" class="tab-pane active" id="da_chon">
        <table id="dachon_sales_table" class="table table-striped table-hover search_table">
                <thead>
                    <tr bgcolor="#CCC">
                        <th>Mã khách hàng</th>
                        <th>Tên khách hàng</th>
                        <th>Nợ đầu kỳ</th>
                        <th>Trả trong kỳ</th>
                        <th>Nợ trong kỳ</th>
                        <th>Tồn cuối kỳ</th>
                        <th>Xem chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $toncuoiky = 0;
                    foreach($datas as $data){
                        $toncuoiky = $toncuoiky + $data['ton_cuoi_ky'];
                        ?>
                         <tr>
                         <td><?php echo $data['ma_hang_hoa']; ?></td>
                         <td><?php echo $data['ten_hang_hoa']; ?></td>
                         <td><?php echo $data['no_dau_ky']; ?></td>
                         <td><?php echo $data['tra_trong_ky']; ?></td>
                         <td><?php echo $data['no_trong_ky']; ?></td>
                         <td><?php echo $data['ton_cuoi_ky']; ?></td>
                         <td><?php echo $data['edit']; ?></td>
                         </tr>
                 <?php       
                    }
                ?>
                <tr>
                    <td colspan="5" style="font-weight: bold">Tổng tồn cuối kỳ</td>
                    <td style="font-weight: bold"><?php echo $toncuoiky; ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
    </div>

</div>