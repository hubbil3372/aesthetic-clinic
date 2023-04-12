<!-- Testimoni -->
<div class="container">
	<div class="row d-flex justify-content-center">
		<div class="col-md-12">
			<div class="card shadow p-3 mb-3 rounded border-0">
				<div class="card-body">
					<?= form_open() ?>
					<div class="row">
						<?php foreach ($produk as $key => $v) { ?>
							<div class="col-md-12">
								<img class="float-start me-2" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
								<span class="d-block mt-4">
									<?= $v->detailProdukNama ?>
								</span>
								<span class="d-block fst-italic fw-bold mt-1 ">
									"<?= $testimoni[$key]->testimoniTeks ?>"
								</span>
								<div class="mb-4">
									<input class="form-control d-none <?= form_error('testimoniId[]') ? 'is-invalid' : null; ?>" name="testimoniId[]" value="<?= $testimoni[$key]->testimoniId ?>">
									<div class="invalid-feedback">
										<?= form_error('testimoniId[]') ?>
									</div>
									<textarea class="form-control <?= form_error('testimoniBalasan[]') ? 'is-invalid' : null; ?>" name="testimoniBalasan[]" rows="2" placeholder="Balas ulasan ini"><?= set_value('testimoniBalasan[]') ? set_value('testimoniBalasan[]') : $testimoni[$key]->testimoniBalasan; ?></textarea>
									<div class="invalid-feedback">
										<?= form_error('testimoniBalasan[]') ?>
									</div>
								</div>
							</div>
						<?php } ?>
						<div class="col-md-12">
							<button type="submit" class="btn btn-primary w-100">
								<i class="fas fa-save"></i> Simpan balasan
							</button>
						</div>
					</div>
					<?= form_close() ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /Testimoni -->