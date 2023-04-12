<div class="container-fluid">
    <section class="row">
        <?php
        $user = $this->ion_auth->user()->row();
        $user_groups = $this->ion_auth->get_users_groups($user->pengId)->row();
        if ($user_groups->id != 5) : ?>
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center w-100">
                        <h3 class="card-title">Laporan Penjualan</h3>
                    </div>
                    <div class="card-body">
                        <?= form_open() ?>
                        <div class="row">
                            <div class="col-md-5">
                                <label for="start" class="form-label">Tanggal Awal</label>
                                <input class="form-control <?= form_error('start') ? 'is-invalid' : null; ?>" type="date" id="start" name="start" value="<?= set_value('start'); ?>">
                                <div class="invalid-feedback">
                                    <?= form_error('start') ?>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label for="end" class="form-label">Tanggal Akhir</label>
                                <input class="form-control <?= form_error('end') ? 'is-invalid' : null; ?>" type="date" id="end" name="end" value="<?= set_value('end'); ?>">
                                <div class="invalid-feedback">
                                    <?= form_error('end') ?>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary d-block w-100 waitme align-self-end">Cetak</button>
                            </div>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header d-flex justify-content-between align-items-center w-100">
                    <h3 class="card-title">Laporan Booking Treatment</h3>
                </div>
                <div class="card-body">
                    <?= form_open('backoffice/laporan/cetak-laporan-treatment', ['method' => 'POST']) ?>
                    <div class="row">
                        <div class="col-md-5">
                            <label for="dateStart" class="form-label">Tanggal Awal</label>
                            <input class="form-control <?= form_error('dateStart') ? 'is-invalid' : null; ?>" type="date" id="dateStart" name="dateStart" value="<?= set_value('dateStart'); ?>">
                            <div class="invalid-feedback">
                                <?= form_error('dateStart') ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label for="dateEnd" class="form-label">Tanggal Akhir</label>
                            <input class="form-control <?= form_error('dateEnd') ? 'is-invalid' : null; ?>" type="date" id="dateEnd" name="dateEnd" value="<?= set_value('dateEnd'); ?>">
                            <div class="invalid-feedback">
                                <?= form_error('dateEnd') ?>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary d-block w-100 waitme align-self-end">Cetak</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </section>
</div>