<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header d-flex justify-content-between align-items-center w-100">
                    <h3 class="card-title">Data <?= $title; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-primary py-2 mb-3">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <td>Nama Customer</td>
                                        <td class="fw-bold"><?= $konsultasi->customerNama ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                        </div>
                        <div class="col-md-12">
                            <div class="card card-body">
                                <h5 class="fw-bold"><?= $konsultasi->konsultasiJudul ?></h5>
                                <p class="mb-0"><?= $konsultasi->konsultasiTeks ?></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <?php
                            foreach ($detail as $key => $value) { ?>
                                <div class="row <?= $value->kdDokterId != null ? 'justify-content-end' : null ?>">
                                    <div class="col-md-10">
                                        <div class="card <?= $value->kdDokterId != null ? 'alert-success' : null ?>">
                                            <div class="card-body">
                                                <p class="mb-0 <?= $value->kdStatusHapus == '1' ? 'fst-italic' : null ?>">
                                                    <?= $value->kdTeks ?>
                                                </p>
                                                <?php if ($value->kdDokterId != null && $value->kdStatusHapus == 0) : ?>
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <a href="<?= site_url("backoffice/konsultasi/{$value->kdId}/hapus-tanggapan") ?>" class="text-danger text-decoration-none align-self-end">
                                                                <small><i class="fas fa-trash"></i></small></a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($value->kdCustomerId != null) : ?>
                                                <div class="card-footer text-primary">
                                                    <i class="fas fa-user-circle me-3"></i> <?= $value->customerNama ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-12">
                            <form action="" method="POST">
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="kdTeks" class="form-label">Tulis Tanggapan</label>
                                            <textarea class="form-control <?= form_error('kdTeks') ? 'is-invalid' : null ?>" id="kdTeks" name="kdTeks" rows="3"><?= set_value('kdTeks') ?></textarea>
                                            <?= form_error('kdTeks', '<div class="invalid-feedback">', '</div>') ?>
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
                </div>
            </div>
        </div>
    </div>
</div>