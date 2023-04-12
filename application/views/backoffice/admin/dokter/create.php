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
                                    <label class="form-label" for="dokterPengId">Pilih Dokter(*) <small class="text-danger">(Dokter Sebelumnya Harus terdaftar Sebagai Pengguna)</small> </label>
                                    <select class="form-control <?= form_error('dokterPengId') ? 'is-invalid' : null; ?>" id="dokterPengId" name="dokterPengId">
                                        <option value="">Pilih</option>
                                        <?php foreach ($pengguna as $p => $peng) : ?>
                                            <option value="<?= $peng->pengId ?>" <?= set_value('dokterPengId') == $peng->pengId ? 'selected' : null; ?>><?= $peng->pengNama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= form_error('dokterPengId') ?>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label" for="dokterNama">Nama Dokter</label>
                                    <input class="form-control <?= form_error('dokterNama') ? 'is-invalid' : null; ?>" id="dokterNama" name="dokterNama" type="text" value="<?= set_value('dokterNama'); ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('dokterNama') ?>
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label" for="dokterSpesialisId">Spesialis(*)</label>
                                    <select class="form-control <?= form_error('dokterSpesialisId') ? 'is-invalid' : null; ?>" id="dokterSpesialisId" name="dokterSpesialisId">
                                        <option value="">Pilih Spesialis</option>
                                        <?php foreach ($spesialis as $s => $spesial) : ?>
                                            <option value="<?= $spesial->spesialisId ?>" <?= set_value('dokterSpesialisId') == $spesial->spesialisId ? 'selected' : null; ?>><?= $spesial->spesialisNama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= form_error('dokterSpesialisId') ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="dokterProfil">Profil Dokter(*)</label>
                                    <textarea rows="5" class="form-control <?= form_error('dokterProfil') ? 'is-invalid' : null; ?>" id="dokterProfil" name="dokterProfil" placeholder="Dokter ini merupakan lulusan terbaik dari universitas kesehatan terkemuka di indonesia"><?= set_value('dokterProfil'); ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= form_error('dokterProfil') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 mb-4">
                                <p class="mb-0 text-danger">(*) Wajib Diisi</p>
                            </div>
                        </div>


                        <button class="btn btn-success waitme" type="submit">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>