<!-- Testimoni -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9">
            <h3 class="fw-bold text-center">Tambah Ulasan</h3>
        </div>
        <div class="col-md-9">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <?= form_open() ?>
                    <div class="row">
                        <?php foreach ($produk as $key => $v) { ?>
                            <div class="col-md-12">
                                <img class="float-start me-2" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
                                <span class="d-block mt-5">
                                    <?= $v->detailProdukNama ?>
                                </span>
                                <div class="mb-4">
                                    <input class="form-control d-none <?= form_error('testimoniProdukId[]') ? 'is-invalid' : null; ?>" name="testimoniProdukId[]" value="<?= $v->detailProdukId ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('testimoniProdukId[]') ?>
                                    </div>
                                    <input class="form-control d-none <?= form_error('testimoniCheckoutId[]') ? 'is-invalid' : null; ?>" name="testimoniCheckoutId[]" value="<?= $v->detailCheckoutId ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('testimoniCheckoutId[]') ?>
                                    </div>
                                    <textarea class="form-control <?= form_error('testimoniTeks[]') ? 'is-invalid' : null; ?>" name="testimoniTeks[]" rows="2" placeholder="Bagaimana ulasanmu tentang produk ini?"><?= set_value('testimoniTeks[]'); ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= form_error('testimoniTeks[]') ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i> Simpan Ulasan
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