<!-- Registrasi -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-center">Registrasi</h3>
            <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                <?= form_open() ?>
                <div class="card-body">
                    <small class="d-block mb-4">Silahkan masukan data diri anda.</small>
                    <div class="mb-4">
                        <label class="form-label" for="customerNama">Nama Lengkap</label>
                        <input class="form-control <?= form_error('customerNama') ? 'is-invalid' : null; ?>" id="customerNama" name="customerNama" type="text" value="<?= set_value('customerNama'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerNama') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerTglLahir">Tanggal Lahir</label>
                        <input class="form-control <?= form_error('customerTglLahir') ? 'is-invalid' : null; ?>" id="customerTglLahir" name="customerTglLahir" type="date" value="<?= set_value('customerTglLahir'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerTglLahir') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="form-check <?= form_error('customerJenisKelamin') ? 'is-invalid' : null; ?>">
                            <input class="form-check-input" type="radio" name="customerJenisKelamin" id="customerJenisKelamin1" value="Laki-laki" <?= set_value('customerJenisKelamin') == 'Laki-laki' ? 'checked' : null ?>>
                            <label class="form-check-label" for="customerJenisKelamin1">
                                Laki-laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="customerJenisKelamin" id="customerJenisKelamin2" value="Perempuan" <?= set_value('customerJenisKelamin') == 'Perempuan' ? 'checked' : null ?>>
                            <label class="form-check-label" for="customerJenisKelamin2">
                                Perempuan
                            </label>
                        </div>
                        <div class="invalid-feedback">
                            <?= form_error('customerJenisKelamin') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerNoHp">Nomor Handphone</label>
                        <input class="form-control <?= form_error('customerNoHp') ? 'is-invalid' : null; ?>" id="customerNoHp" name="customerNoHp" type="number" value="<?= set_value('customerNoHp'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerNoHp') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerEmail">Email</label>
                        <input class="form-control <?= form_error('customerEmail') ? 'is-invalid' : null; ?>" id="customerEmail" name="customerEmail" type="email" value="<?= set_value('customerEmail'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerEmail') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerPassword">Password</label>
                        <input class="form-control <?= form_error('customerPassword') ? 'is-invalid' : null; ?>" id="customerPassword" name="customerPassword" type="password" value="<?= set_value('customerPassword'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerPassword') ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary w-100 waitme mb-3"><i class="fa fa-plus" aria-hidden="true"></i> Registrasi</i></button>

                    Sudah memiliki akun? <a class="text-decoration-none waitme" href="<?= base_url('login') ?>">Login Sekarang!</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- /Registrasi -->