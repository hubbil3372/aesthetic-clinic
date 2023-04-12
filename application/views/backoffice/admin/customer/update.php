<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header d-flex justify-content-between align-items-center w-100">
					<h3 class="card-title">Data <?= $title; ?></h3>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<span class="d-block h5 fw-bold mb-1 mt-3">PROFIL</span>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Foto</span>
									<?php if ($customer->customerFoto != 'default.png') : ?>
										<img class="img img-fluid" width="100px" src="<?= base_url('_uploads/profil/' . $customer->customerFoto) ?>">
									<?php else : ?>
										Foto Tidak Tersedia
									<?php endif; ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Nama Lengkap</span>
									<?= $customer->customerNama ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Tanggal Lahir</span>
									<?= $customer->customerTglLahir ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Jenis Kelamin</span>
									<?= $customer->customerJenisKelamin ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Email</span>
									<?= $customer->customerEmail ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">No Hp</span>
									<?= $customer->customerNoHp ?>
								</li>
							</ul>
						</div>
						<div class="col-md-6">
							<ul class="list-group list-group-flush">
								<li class="list-group-item">
									<span class="d-block h5 fw-bold mb-1 mt-3">PENGIRIMAN</span>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Nama Penerima</span>
									<?= $customer->customerAlamatPenerima ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Provinsi</span>
									<?= $customer->customerAlamatProvinsiNama ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Kota / Kabupaten</span>
									<?= $customer->customerAlamatKotkabNama ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Kecamatan</span>
									<?= $customer->customerAlamatKecamatanNama ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">No Hp Penerima</span>
									<?= $customer->customerAlamatNoHp ?>
								</li>
								<li class="list-group-item">
									<span class="d-block fw-bold mb-1 mt-3">Alamat Lengkap</span>
									<?= $customer->customerAlamatLengkap ?>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>