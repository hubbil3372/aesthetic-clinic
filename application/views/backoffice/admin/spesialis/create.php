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
                                    <label class="form-label" for="spesialisNama">Nama Spesialis</label>
                                    <input class="form-control <?= form_error('spesialisNama') ? 'is-invalid' : null; ?>" id="spesialisNama" name="spesialisNama" type="text" value="<?= set_value('spesialisNama'); ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('spesialisNama') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label" for="spesialisDeskripsi">Deskripsi</label>
                                    <input class="form-control <?= form_error('spesialisDeskripsi') ? 'is-invalid' : null; ?>" id="spesialisDeskripsi" name="spesialisDeskripsi" type="text" value="<?= set_value('spesialisDeskripsi'); ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('spesialisDeskripsi') ?>
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