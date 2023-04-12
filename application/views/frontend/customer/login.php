<!-- Login -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-center">Login</h3>
            <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                <?= form_open() ?>
                <div class="card-body">
                    <small class="d-block mb-4">Silahkan masukan email dan password anda.</small>
                    <div class="mb-3">
                        <label for="customerEmail" class="form-label">Email</label>
                        <input type="email" class="form-control <?= form_error('customerEmail') ? 'is-invalid' : null; ?>" id="customerEmail" name="customerEmail" placeholder="nama@contoh.com" value="<?= set_value('customerEmail'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerEmail') ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="customerPassword" class="form-label">Password</label>
                        <input type="password" class="form-control <?= form_error('customerPassword') ? 'is-invalid' : null; ?>" id="customerPassword" name="customerPassword" value="<?= set_value('customerPassword'); ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerPassword') ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary w-100 waitme mb-3"><i class="fa fa-sign-in" aria-hidden="true"></i> Login</i></button>

                    Belum memiliki akun? <a class="text-decoration-none waitme" href="<?= base_url('registrasi') ?>">Registrasi Sekarang!</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- /Login -->