<div class="container mt-5 min-vh-100">
    <div class="row mb-5 justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="">Kritik dan Saran</h4>
                <a href="#" onclick="window.history.back()" class="btn btn-outline-primary waitme"> <i class="fas fa-angle-left"></i> Kembali</a>
            </div>
            <div class="card rounded mb-4 shadow-ea-bottom">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="card-title mb-0 text-primary fw-bold">Buat Kritik dan Saran</h5>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="saranJudul" class="form-label">Judul Kritik/Saran</label>
                                <textarea class="form-control <?= form_error('saranJudul') ? 'is-invalid' : null ?>" id="saranJudul" name="saranJudul" rows="2"><?= set_value('saranJudul') ?></textarea>
                                <?= form_error('saranJudul', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="saranText" class="form-label">Detail Kritik/Saran</label>
                                <textarea class="form-control <?= form_error('saranText') ? 'is-invalid' : null ?>" id="saranText" name="saranText" rows="5"><?= set_value('saranText') ?></textarea>
                                <?= form_error('saranText', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="row justify-content-end">
                                <div class="col-md-4 d-grid">
                                    <button type="submit" class="btn btn-primary waitme">Kirim</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>