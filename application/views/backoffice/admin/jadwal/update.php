<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info card-outline">
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
                                    <label class="form-label" for="jdDokterId">Pilih Dokter</label>
                                    <input type="hidden" id="jdId" name="jdId" value="<?= $this->input->post('jdId') ?? $jadwal->jdId ?>">
                                    <select class="form-control select2 <?= form_error('jdDokterId') ? 'is-invalid' : null; ?>" id="jdDokterId" name="jdDokterId" data-placeholder="Pilih Dokter">
                                        <option value=""></option>
                                        <?php foreach ($dokter as $key => $value) : ?>
                                            <option value="<?= $value->dokterId ?>" <?= $this->input->post('jdDokterId') != null ? ($this->input->post('jdDokterId') == $value->dokterId ? 'selected' : null) : ($jadwal->jdDokterId == $value->dokterId ? 'selected' : null) ?>><?= $value->dokterNama ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= form_error('jdDokterId') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="jdJamAwal">Jam Awal Masuk</label>
                                    <input type="time" class="form-control <?= form_error('jdJamAwal') ? 'is-invalid' : null; ?>" id="jdJamAwal" name="jdJamAwal" value="<?= $this->input->post('jdJamAwal') ?? $jadwal->jdJamAwal ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('jdJamAwal') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="jdJamAkhir">Jam Awal Selesai</label>
                                    <input type="time" class="form-control <?= form_error('jdJamAkhir') ? 'is-invalid' : null; ?>" id="jdJamAkhir" name="jdJamAkhir" value="<?= $this->input->post('jdJamAkhir') ?? $jadwal->jdJamAkhir ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('jdJamAkhir') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="jdBatasAntrian">Batas Anatrian</label>
                                    <input type="number" class="form-control <?= form_error('jdBatasAntrian') ? 'is-invalid' : null; ?>" id="jdBatasAntrian" name="jdBatasAntrian" value="<?= $this->input->post('jdBatasAntrian') ?? $jadwal->jdBatasAntrian ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('jdBatasAntrian') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="jdStatus">Status Jadwal</label>
                                    <select class="form-control <?= form_error('jdStatus') ? 'is-invalid' : null; ?>" id="jdStatus" name="jdStatus">
                                        <option value="0">Tidak Aktif</option>
                                        <option value="1" <?= $this->input->post('jdStatus') != null ? ($this->input->post('jdStatus') == '1' ? 'selected' : null) : ($jadwal->jdStatus == '1' ? 'selected' : null) ?>>Aktif</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= form_error('jdStatus') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-info px-5 waitme text-white float-end" type="submit"><i class="fas fa-check-circle"></i> Simpan Perubahan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>