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
                  <label class="form-label" for="kategoriNama">Kategori</label>
                  <input class="form-control <?= form_error('kategoriNama') ? 'is-invalid' : null; ?>" id="kategoriNama" name="kategoriNama" type="text" value="<?= $this->input->post('kategoriNama') ?? $kategori->kategoriNama; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('kategoriNama') ?>
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