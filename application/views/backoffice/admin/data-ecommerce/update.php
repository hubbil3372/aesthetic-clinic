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
                  <label class="form-label" for="ecomNama">Nama Ecommerce</label>
                  <input class="form-control <?= form_error('ecomNama') ? 'is-invalid' : null; ?>" id="ecomNama" name="ecomNama" type="text" value="<?= $this->input->post('ecomNama') ?? $ecom->ecomNama; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('ecomNama') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="ecomNoHandphone">No Handphone</label>
                  <input class="form-control <?= form_error('ecomNoHandphone') ? 'is-invalid' : null; ?>" id="ecomNoHandphone" name="ecomNoHandphone" type="number" value="<?= $this->input->post('ecomNoHandphone') ?? $ecom->ecomNoHandphone; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('ecomNoHandphone') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="ecomAlamatKotkabId">Kota / Kabupaten</label>
                  <select class="form-select <?= form_error('ecomAlamatKotkabId') ? 'is-invalid' : null; ?>" id="ecomAlamatKotkabId" name="ecomAlamatKotkabId">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($cities as $key => $v) { ?>
                      <option value="<?= $v->city_id ?>" <?= $ecom->ecomAlamatKotkabId == $v->city_id ? 'selected' : null ?>><?= $v->type . ' ' . $v->city_name ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback">
                    <?= form_error('ecomAlamatKotkabId') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="ecomAlamatLengkap">Alamat Lengkap</label>
                  <textarea class="form-control <?= form_error('ecomAlamatLengkap') ? 'is-invalid' : null; ?>" id="ecomAlamatLengkap" name="ecomAlamatLengkap"><?= $this->input->post('ecomAlamatLengkap') ?? $ecom->ecomAlamatLengkap; ?></textarea>
                  <div class="invalid-feedback">
                    <?= form_error('ecomAlamatLengkap') ?>
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