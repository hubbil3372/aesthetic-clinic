<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <form class="align-self-center" action="" method="post">

            <!-- ------------------------------------------------ -->
            <!-- CSRF TOKEN -->
            <!-- ------------------------------------------------ -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row">
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="kurirNama">Kurir</label>
                  <input class="form-control <?= form_error('kurirNama') ? 'is-invalid' : null; ?>" id="kurirNama" name="kurirNama" type="text" value="<?= set_value('kurirNama'); ?>">
                  <div class="invalid-feedback">
                    <?= form_error('kurirNama') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="kurirKode">Kode</label>
                  <input class="form-control <?= form_error('kurirKode') ? 'is-invalid' : null; ?>" id="kurirKode" name="kurirKode" type="text" value="<?= set_value('kurirKode'); ?>">
                  <div class="invalid-feedback">
                    <?= form_error('kurirKode') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label">Status</label>
                  <div class="form-check <?= form_error('kurirStatus') ? 'is-invalid' : null; ?>">
                    <input class="form-check-input" type="radio" name="kurirStatus" id="kurirStatus1" value="1" checked>
                    <label class="form-check-label" for="kurirStatus1">
                      Aktif
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="kurirStatus" id="kurirStatus2" value="0">
                    <label class="form-check-label" for="kurirStatus2">
                      Tidak Aktif
                    </label>
                  </div>
                  <div class="invalid-feedback">
                    <?= form_error('kurirStatus') ?>
                  </div>
                </div>
              </div>
            </div>

            <button class="btn btn-success waitme" type="submit">Simpan</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>