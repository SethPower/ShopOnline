<?php if($_settings->chk_flashdata('success')): ?>
<script>
	alert_toast("<?php echo $_settings->flashdata('success') ?>",'success')
</script>
<?php endif;?>
<style>
	.product-img{
		width: calc(100%);
		height: auto;
		max-width: 5em;
		object-fit:scale-down;
		object-position:center center;
	}
</style>
<div class="card card-outline card-primary">
	<div class="card-header">
		<h3 class="card-title">Danh sách sản phẩm</h3>
	</div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-bordered table-stripped">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="10%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr class="bg-gradient-secondary">
						<th>#</th>
						<th>Ngày tạo</th>
						<th>Hình ảnh</th>
						<th>Nhà cung cấp/Sản phẩm</th>
						<th>Giá</th>
						<th>Trạng thái</th>
						<th>Hành động</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i = 1;
						$qry = $conn->query("SELECT p.*,v.code, v.shop_name as `vendor` from `product_list` p inner join vendor_list v on p.vendor_id = v.id where p.delete_flag = 0 order by p.`name` asc ");
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td class="text-center"><img src="<?= validate_image($row['image_path']) ?>" alt="Product Image" class="border border-gray img-thumbnail product-img"></td>
							<td>
								<div class="border-bottom text-truncate"><?= $row['code'].'-'.$row['vendor'] ?></div>
								<div class="text-truncate"><?= $row['name'] ?></div>
							</td>
							<td class="text-right"><?php echo format_num($row['price']) ?></td>
							<td class="text-center">
                                <?php if($row['status'] == 1): ?>
                                    <span class="badge badge-danger bg-gradient-danger px-3 rounded-pill">Chờ duyệt</span>
                                <?php else: ?>
                                    <span class="badge badge-success bg-gradient-success px-3 rounded-pill">Duyệt</span>
                                <?php endif; ?>
                            </td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Hành động
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> Xem</a>
				                    <div class="dropdown-divider"></div>
				                    <a class="dropdown-item edit_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-edit text-primary"></span> Sửa</a>
				                  </div>

							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#create_new').click(function(){
			uni_modal('Thêm sản phẩm mới',"products/manage_product.php",'large')
		})
		$('.view_data').click(function(){
			uni_modal('Xem chi tiết sản phẩm',"products/view_product.php?id="+$(this).attr('data-id'),'large')
		})
		$('.edit_data').click(function(){
			uni_modal('Cập nhật sản phẩm',"products/manage_products.php?id="+$(this).attr('data-id'),'large')
		})
		$('.delete_data').click(function(){
			_conf("Bạn có chắc xóa sản phẩm này vĩnh viễn?","delete_product",[$(this).attr('data-id')])
		})
		$('table th,table td').addClass('align-middle px-2 py-1')
		$('.table').dataTable();
	})
	function delete_product($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_product",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>