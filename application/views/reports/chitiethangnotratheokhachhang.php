<div class="card">
    <div role="tabpanel" class="tab-pane active" id="da_chon">
        <table id="dachon_sales_table" class="table table-striped table-hover search_table">
                <thead>
                    <tr bgcolor="#CCC">
                        <th>Mã hàng hóa</th>
                        <th>Tên hàng hóa</th>
                        <th>Nợ đầu kỳ</th>
                        <th>Trả trong kỳ</th>
                        <th>Nợ trong kỳ</th>
                        <th>SL Điều chỉnh</th> 
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
                            <?php if(!$role){ ?>
                                <td><?php echo $data['sl_dieu_chinh']; ?></td> 
                            <?php }else{ ?>
                                <td><input value="<?php echo $data['sl_dieu_chinh']; ?>" onchange="change_value(this,'<?php echo $person_id; ?>','<?php echo $data['id_item']; ?>','<?php echo $data['ton_cuoi_ky']; ?>')" type="text" name="hanggui" class="form-control input-sm"></td>
                            <?php } ?>
                            <td id="ton-cuoi-ky-<?php echo $data['person_id']; ?>"><?php echo $data['ton_cuoi_ky']; ?></td>
                            <td><?php echo $data['edit']; ?></td>
                         </tr>
                 <?php       
                    }
                ?>
                <tr>
                    <td colspan="6" style="font-weight: bold">Tổng tồn cuối kỳ</td>
                    <td style="font-weight: bold"><?php echo $toncuoiky; ?></td>
                    <td></td>
                </tr>
                </tbody>
            </table>
    </div>

</div>

<script>
function change_value(sel,person_id,item_id,toncuoiky){
    $.ajax({
        url : '<?php echo site_url('reports/dieuchinhhangnotra');?>',
        type : "get",
        dataType:"json",
        data : {
            value: sel.value,
            person_id: person_id,
            item_id: item_id
        },
        success : function (arrResult){
            var toncuoiky_new = +toncuoiky + +sel.value;
            $("#ton-cuoi-ky-"+person_id).html(toncuoiky_new);

        }
    });
}
</script>