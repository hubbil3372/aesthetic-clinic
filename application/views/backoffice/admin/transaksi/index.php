<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title">Data <?= $title; ?></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table">
							<thead>
								<tr>
									<th class="text-center" style="width: 5%;">No</th>
									<th>Kode</th>
									<th>Customer</th>
									<th>Status Bayar</th>
									<th>Status Pengiriman</th>
									<th>Total Tagihan</th>
									<th class="text-center">Aksi</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	// --------------------------------------
	// CSRF TOKEN
	// --------------------------------------
	var csfrData = {};
	csfrData['<?= $this->security->get_csrf_token_name(); ?>'] = '<?= $this->security->get_csrf_hash(); ?>';
	$.ajaxSetup({
		data: csfrData
	});

	var table;
	$(document).ready(function() {
		table = $('#table').DataTable({
			"processing": true,
			"serverSide": true,
			"order": [],
			"ajax": {
				"url": "<?= site_url("backoffice/admin/transaksi/get_json?tautan={$this->uri->segment(2)}") ?>",
				"type": "POST"
			},
			"columnDefs": [{
				"targets": [0],
				"orderable": false,
			}, ],
		});
	});
</script>