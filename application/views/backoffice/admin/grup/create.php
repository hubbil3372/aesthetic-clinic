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
                  <label class="form-label" for="grupNama">Grup</label>
                  <input class="form-control <?= form_error('grupNama') ? 'is-invalid' : null; ?>" id="grupNama" name="grupNama" type="text" value="<?= set_value('grupNama'); ?>">
                  <div class="invalid-feedback">
                    <?= form_error('grupNama') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mb-4">
                  <label class="form-label" for="grupDeskripsi">Deskripsi</label>
                  <input class="form-control <?= form_error('grupDeskripsi') ? 'is-invalid' : null; ?>" id="grupDeskripsi" name="grupDeskripsi" type="text" value="<?= set_value('grupDeskripsi'); ?>">
                  <div class="invalid-feedback">
                    <?= form_error('grupDeskripsi') ?>
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
