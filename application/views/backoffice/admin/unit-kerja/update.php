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
                  <label class="form-label" for="ukNama">Unit Kerja</label>
                  <input class="form-control <?= form_error('ukNama') ? 'is-invalid' : null; ?>" id="ukNama" name="ukNama" type="text" value="<?= $this->input->post('ukNama') ?? $work_unit->ukNama; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('ukNama') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="ukDeskripsi">Deskripsi</label>
                  <input class="form-control <?= form_error('ukDeskripsi') ? 'is-invalid' : null; ?>" id="ukDeskripsi" name="ukDeskripsi" type="text" value="<?= $this->input->post('ukDeskripsi') ?? $work_unit->ukDeskripsi; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('ukDeskripsi') ?>
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