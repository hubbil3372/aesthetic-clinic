<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title"><?= $title; ?></h3>
        </div>
        <div class="card-body">
          <form class="align-self-center" action="" method="post" enctype="multipart/form-data">

            <!-- ------------------------------------------------ -->
            <!-- CSRF TOKEN -->
            <!-- ------------------------------------------------ -->
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row">
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="voucherNama">Voucher</label>
                  <input class="form-control <?= form_error('voucherNama') ? 'is-invalid' : null; ?>" id="voucherNama" name="voucherNama" type="text" value="<?= $this->input->post('voucherNama') ?? $voucher->voucherNama; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('voucherNama') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="voucherKode">Kode</label>
                  <input class="form-control <?= form_error('voucherKode') ? 'is-invalid' : null; ?>" id="voucherKode" name="voucherKode" type="text" value="<?= $this->input->post('voucherKode') ?? $voucher->voucherKode; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('voucherKode') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="voucherPotongan">Potongan</label>
                  <input class="form-control <?= form_error('voucherPotongan') ? 'is-invalid' : null; ?>" id="voucherPotongan" name="voucherPotongan" type="text" value="<?= $this->input->post('voucherPotongan') ?? $voucher->voucherPotongan; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('voucherPotongan') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="voucherGambar">Gambar</label>
                  <img class="img img-fluid w-25 d-block mb-3" src="<?= base_url('_uploads/voucher/' . $voucher->voucherGambar) ?>">
                  <input class="form-control <?= form_error('voucherGambar') ? 'is-invalid' : null; ?>" id="voucherGambar" name="voucherGambar" type="file" accept="image/png, image/jpg, image/jpeg">
                  <div class="invalid-feedback">
                    <?= form_error('voucherGambar') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label">Status</label>
                  <div class="form-check <?= form_error('voucherStatus') ? 'is-invalid' : null; ?>">
                    <input class="form-check-input" type="radio" name="voucherStatus" id="voucherStatus1" value="1" <?= $voucher->voucherStatus == 1 ? 'checked' : null ?>>
                    <label class="form-check-label" for="voucherStatus1">
                      Aktif
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="voucherStatus" id="voucherStatus2" value="0" <?= $voucher->voucherStatus == 0 ? 'checked' : null ?>>
                    <label class="form-check-label" for="voucherStatus2">
                      Tidak Aktif
                    </label>
                  </div>
                  <div class="invalid-feedback">
                    <?= form_error('voucherStatus') ?>
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