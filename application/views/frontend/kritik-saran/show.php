<div class="container mt-5 min-vh-100">
    <div class="row mb-5 justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="">Kritik dan Saran</h4>
                <a href="<?= site_url('kritik-saran') ?>" class="btn btn-outline-primary waitme"> <i class="fas fa-arrow-left"></i> Kembali Ke Saran</a>
            </div>
            <div class="card rounded mb-4">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0 text-primary fw-bold"> <i class="fas fa-question-circle fw-bold"></i> <?= $saran->saranJudul ?></h5>
                </div>
                <div class="card-body">
                    <p><?= $saran->saranText ?></p>
                </div>
            </div>
            <h5 class="fw-bold">Tanggapan :</h5>
            <?php if (count((array)$detail) < 1) : ?>
                <div class="card card-body">
                    <p class="text-primary mb-0 text-center">Tanggapan Belum tersedia</p>
                </div>
                <?php else : foreach ($detail as $key => $value) :
                    if ($value->sdCustomerId == null) :  ?>
                        <div class="card rounded-19 mb-4 me-5 shadow-ea-top">
                            <div class="card-body">
                                <?= $value->sdText ?>
                            </div>
                            <div class="card-footer bg-white rounded-19">
                                <i class="fas fa-user-circle fa-2xl d-inline-block me-2"></i>
                                <h5 class="mb-0 card-title text-primary fw-bold d-inline-block"><?= $value->pengNama ?></h5>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="card rounded-19 mb-3 bg-primary ms-5 shadow-ea-top">
                            <div class="card-body text-white">
                                <?= $value->sdText ?>
                            </div>
                            <div class="card-footer bg-none rounded-19 text-end">
                                <i class="fas fa-user-circle text-white d-inline-block me-2"></i>
                                <small class="mb-0 card-title fw-bold text-white d-inline-block"><?= $value->customerNama ?></small>
                            </div>
                        </div>
                <?php endif;
                endforeach; ?>
                <div class="row">
                    <div class="col-12">
                        <form action="" method="POST">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="sdText" class="form-label">Tulis Tanggapan</label>
                                        <textarea class="form-control <?= form_error('sdText') ? 'is-invalid' : null ?>" id="sdText" name="sdText" rows="3"><?= set_value('sdText') ?></textarea>
                                        <?= form_error('sdText', '<div class="invalid-feedback">', '</div>') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-md-4 d-grid">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>