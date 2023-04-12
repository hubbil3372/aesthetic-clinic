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
                                        <td class="fw-bold"><?= $saran->customerNama ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                        </div>
                        <div class="col-md-12">
                            <div class="card card-body">
                                <h5 class="fw-bold"><?= $saran->saranJudul ?></h5>
                                <p class="mb-0"><?= $saran->saranText ?></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <?php foreach ($detail as $key => $value) { ?>
                                <div class="row <?= $value->sdAdminId != null ? 'justify-content-end' : null ?>">
                                    <div class="col-md-10">
                                        <div class="card <?= $value->sdAdminId != null ? 'alert-success' : null ?>">
                                            <div class="card-body">
                                                <a href="" class=""></a>
                                                <?= $value->sdText ?>
                                                <?php if ($value->sdAdminId != null && $value->sdStatusHapus == 0) : ?>
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <a href="<?= site_url("backoffice/kritik-saran/{$value->sdId}/hapus-tanggapan") ?>" class="text-danger text-decoration-none align-self-end">
                                                                <small><i class="fas fa-trash"></i></small></a>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($value->sdCustomerId != null) : ?>
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
                </div>
            </div>
        </div>
    </div>
</div>