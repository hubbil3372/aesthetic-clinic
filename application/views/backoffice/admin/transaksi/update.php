<!-- Transaksi -->
<div class="container">
	<div class="row d-flex justify-content-center">
		<div class="col-md-8">
			<div class="card shadow p-3 mb-3 rounded border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4 mb-3 pb-3 border-bottom">
							<span class="d-block">Kode Transaksi</span>
							<b class="mb-3"><?= $transaksi->checkoutKode ?></b>
						</div>
						<div class="col-md-4 mb-3 pb-3 border-bottom">
							<span class="d-block">Total Tagihan</span>
							<b class="mb-3">Rp<?= $transaksi->checkoutTotalTagihan ?></b>
						</div>
						<div class="col-md-4 mb-3 pb-3 border-bottom text-end">
							<span class="d-block">Status Pesanan</span>
							<?php if ($transaksi->checkoutStatusBayar == 0) { ?>
								<b class="mb-3 text-warning">Menunggu pembayaran</b>
							<?php } ?>
							<?php if ($transaksi->checkoutStatusBayar == 1 and $transaksi->checkoutStatusPengiriman == 0) { ?>
								<b class="mb-3 text-success">Sudah dibayar</b>
							<?php } ?>
							<?php if ($transaksi->checkoutStatusBayar == 1 and $transaksi->checkoutStatusPengiriman == 1) { ?>
								<b class="mb-3 text-info">Sedang dikirim</b>
							<?php } ?>
							<?php if ($transaksi->checkoutStatusBayar == 1 and $transaksi->checkoutStatusPengiriman == 2) { ?>
								<b class="mb-3 text-success">Pesanan sudah sampai</b>
							<?php } ?>
							<?php if ($transaksi->checkoutStatusBayar == 2) { ?>
								<b class="mb-3 text-danger">Bukti pembayaran ditolak</b>
							<?php } ?>
						</div>
						<div class="col-md-12 mt-3 text-center">
							<span class="d-block mb-3 fw-bold">Bukti Pembayaran</span>
							<div class="mb-3">
								<center class="<?= $transaksi->checkoutBuktiBayar == null ? 'd-none' : null ?>">
									<a href="<?= base_url('_uploads/bukti_bayar/' . $transaksi->checkoutBuktiBayar) ?>" target="_blank">
										<img class="img img-fluid h-25 w-25 d-block" src="<?= base_url('_uploads/bukti_bayar/' . $transaksi->checkoutBuktiBayar) ?>">
									</a>
									<div class="<?= $transaksi->checkoutStatusBayar == 1 ? 'd-none' : null ?>">
										<span class="d-block my-2">
											Terima / Tolak ?
										</span>
										<a class="btn btn-success" href="<?= base_url('backoffice/transaksi/status-bayar/' . $transaksi->checkoutId . '?status=terima') ?>">
											<i class="fas fa-check"></i>
										</a>
										<a class="btn btn-danger" href="<?= base_url('backoffice/transaksi/status-bayar/' . $transaksi->checkoutId . '?status=tolak') ?>">
											<i class="fas fa-times"></i>
										</a>
									</div>
								</center>
							</div>
						</div>
						<div class="col-md-12 mb-3 pt-4 mt-3 border-top">
							<b class="mb-3 fs-5">Alamat Pengiriman</b>
						</div>
						<div class="col-md-5 mb-3 pb-3">
							<b class="d-block">Nama Penerima</b>
							<span class="d-block"><?= $transaksi->checkoutAlamatPenerima ?></span>
							<small class="mb-3"><?= $transaksi->checkoutAlamatNoHp ?></small>
						</div>
						<div class="col-md-7 mb-3 pb-3">
							<b class="d-block">Alamat Lengkap</b>
							<span class="d-block"><?= $transaksi->checkoutAlamatLengkap ?></span>
							<small class="mb-3">
								<?= $transaksi->checkoutAlamatKecamatanNama ?>, <?= $transaksi->checkoutAlamatKotkabNama ?>, Provinsi <?= $transaksi->checkoutAlamatProvinsiNama ?>
							</small>
						</div>
						<div class="col-md-12 mb-3 pt-4 mt-3 border-top">
							<b class="mb-3 fs-5">Catatan Pengiriman</b>
						</div>
						<div class="col-md-12">
							<div class="mb-3">
								<textarea class="form-control bg-light" rows="2" readonly><?= $transaksi->checkoutCatatan ?></textarea>
							</div>
						</div>
						<div class="col-md-12 mb-3 pt-4 mt-3 border-top">
							<b class="mb-3 fs-5">Detail Produk</b>
						</div>
						<?php foreach ($transaksi_det as $key => $v) { ?>
							<div class="col-md-9">
								<img class="float-start" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
								<span class="d-block mt-4">
									<?= $v->detailProdukNama ?>
								</span>
								<small class="d-block text-secondary">Jumlah: <?= $v->detailKuantitas ?></small>
								<small class="d-block text-secondary">Berat: <?= $v->detailBerat ?> gram</small>
							</div>
							<div class="col-3 text-end">
								<span class="d-block fw-bold mt-4">
									Rp<?= $v->detailTotalHarga ?>
								</span>
								<?php if ($v->detailProdukDiskon != 0) { ?>
									<span class="small text-decoration-line-through text-secondary">
										Rp<?= $v->detailProdukHarga ?>
									</span>
								<?php } ?>
							</div>
						<?php } ?>
						<div class="col-md-12">
							<ul class="list-group list-group-flush border-top mt-3">
								<li class="list-group-item">
									<div class="form-check">
										<label class="form-check-label">
											Ongkos Kirim
										</label>
										<span class="float-end fw-bold">Rp<?= $transaksi->checkoutOngkir ?></span>
									</div>
								</li>
								<li class="list-group-item">
									<div class="form-check">
										<label class="form-check-label">
											Potongan Voucher
										</label>
										<span class="float-end fw-bold">-Rp<?= $transaksi->checkoutVoucherPotongan ?></span>
									</div>
								</li>
							</ul>
						</div>
						<div class="col-md-12 mb-3 pt-4 mt-3 border-top">
							<b class="mb-3 fs-5">Ulasan Customer</b>
						</div>
						<?php foreach ($testimoni as $key => $v) { ?>
							<div class="col-md-12 <?= $v->testimoniTeks ? null : 'd-none' ?>">
								<img class="float-start me-2" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
								<span class="d-block mt-3">
									<?= $v->detailProdukNama ?>
								</span>
								<span class="d-block fst-italic fw-bold mt-1 ">
									"<?= $v->testimoniTeks ?>"
								</span>
								<span class="d-block fst-italic small <?= $v->testimoniBalasan ? null : 'd-none' ?>">
									[Admin] "<?= $v->testimoniBalasan ?>"
								</span>
							</div>
						<?php } ?>
						<div class="col-md-12 <?= $testimoni ? 'd-none' : null ?>">
							Belum ada ulasan
						</div>
						<div class="col-md-12 <?= $testimoni ? null : 'd-none' ?>">
							<a href="<?= base_url('backoffice/transaksi/' . $transaksi->checkoutId . '/testimoni') ?>" class="btn btn-primary w-100" type="button">
								<i class="fa fa-commenting" aria-hidden="true"></i> Balas ulasan
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<div class="card shadow p-3 mb-3 rounded border-0">
				<div class="card-body">
					<div class="row">
						<div class="col-md-12 mb-3 pb-3 border-bottom">
							<span class="d-block">Jasa Pengiriman</span>
							<b class="mb-3"><?= $transaksi->kurirNama ?></b>
						</div>
						<div class="col-md-12 mb-3 pb-3 border-bottom">
							<span class="d-block">No Resi</span>
							<?= form_open(base_url('backoffice/transaksi/update-resi/' . $transaksi->checkoutId)) ?>
							<div class="input-group mb-3">
								<input class="form-control text-primary" type="text" id="checkoutNoResi" name="checkoutNoResi" value="<?= $transaksi->checkoutNoResi ?>">
								<button class="input-group-text btn-primary" type="submit">Simpan</button>
							</div>
							<?= form_close() ?>
						</div>
						<div class="col-md-12 mb-3 pb-3 border-bottom">
							<span class="d-block">Status Pengiriman</span>
							<b class="mb-3"><?= isset($pengiriman->summary->status) ? $pengiriman->summary->status  : '-' ?></b>
						</div>
						<div class="col-md-12 mb-3 pb-3">
							<span class="d-block mb-2">Histori Pengiriman</span>
							<?php if (isset($pengiriman->manifest)) { ?>
								<?php foreach ($pengiriman->manifest as $key => $v) { ?>
									<p class="small">
										<?= date('d M Y', strtotime($v->manifest_date)) ?><br>
										<span class="d-block fw-bold">
											<?= '[' . $v->manifest_code . '] ' . $v->manifest_description ?>
										</span>
										<span class="d-block">
											<?= $v->city_name ?>
										</span>
									</p>
								<?php } ?>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Transaksi -->