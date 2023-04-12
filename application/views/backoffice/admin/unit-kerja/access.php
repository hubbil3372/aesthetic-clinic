<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-borderless">
							<tr>
								<td>Nama</td>
								<td>:</td>
								<td><?= $user->pengNama; ?></td>
							</tr>
							<tr>
								<td>Alamat Email</td>
								<td>:</td>
								<td><?= $user->pengEmail; ?></td>
							</tr>
							<tr>
								<td>Telepon</td>
								<td>:</td>
								<td><?= $user->pengTlp; ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title">Data <?= $title; ?></h3>
					<a class="btn btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal">Tambah Unit Kerja</a>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="table1">
							<thead>
								<tr>
									<th class="text-center" style="width: 5%;">No</th>
									<th>Unit Kerja</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($user_work_units as $key => $user_work_unit) : ?>
									<tr>
										<td class="text-center"><?= $key + 1; ?></td>
										<td><?= $user_work_unit->ukNama; ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Unit Kerja</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-bordered" id="table2">
						<thead>
							<tr>
								<th class="text-center" style="width: 5%;">No</th>
								<th>Unit Kerja</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($work_units as $key => $work_unit) : ?>
								<tr>
									<td class="text-center"><?= $key + 1; ?></td>
									<td><?= $work_unit->ukNama; ?></td>
									<td class="text-center">
										<?php if ($this->akses->is_check_uk($user->pengId, $work_unit->ukId)) : ?>
											<a class="btn btn-sm btn-danger me-1 waitme" style="width: 40px;" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$user->pengId}/pengguna/{$work_unit->ukId}/unit-kerja"); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
										<?php else: ?>
											<a class="btn btn-sm btn-primary me-1 waitme" style="width: 40px;" href="<?= site_url("backoffice/hak-akses/{$group->grupId}/grup/{$user->pengId}/pengguna/{$work_unit->ukId}/unit-kerja"); ?>"><i class="fas fa-edit"></i></a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#table1').DataTable();
		$('#table2').DataTable();
	})
</script>