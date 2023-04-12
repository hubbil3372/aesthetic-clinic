<div class="container mt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12">
            <h3 class="fw-bold text-center"><?= $title ?></h3>
        </div>
        <div class="col-md-12">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?= form_open() ?>
                            <div class="mb-3">
                                <label for="konsultasiJudul" class="form-label">Judul Konsultasi</label>
                                <input type="text" class="form-control <?= form_error('konsultasiJudul') ? 'is-invalid' : null ?>" id="konsultasiJudul" name="konsultasiJudul" placeholder="wajah bengkak dan bopeng setelah jerawat" value="<?= set_value('konsultasiJudul') ?>">
                                <?= form_error('konsultasiJudul', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                            <div class="mb-3">
                                <label for="konsultasiTeks" class="form-label">Konsultasi</label>
                                <textarea class="form-control <?= form_error('konsultasiTeks') ? 'is-invalid' : null ?>" id="konsultasiTeks" name="konsultasiTeks" rows="8"><?= set_value('konsultasiTeks') ?></textarea>
                                <?= form_error('konsultasiTeks', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                            <button type="submit" class="btn btn-primary waitme"><i class="fas fa-paper-plane waitme"></i> Kirim</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>