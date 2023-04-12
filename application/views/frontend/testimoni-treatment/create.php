<div class="container mt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-12">
            <h3 class="fw-bold text-center"><?= $title ?></h3>
        </div>
        <div class="col-md-12">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-12">
                            <label for="labelTreatment" class="form-label">Treatment</label>
                            <div class="card">
                                <div class="card-body">
                                    <img class="float-start" src="<?= base_url('_uploads/treatment/' . $booking->bdTreatmentFoto) ?>" width="100px">
                                    <span class="d-inline-block ms-4">
                                        <?= $booking->bdTreatmentNama ?>
                                        <span class="d-block fw-bold">
                                            Rp<?= $booking->bdTreatmentHarga - $booking->bdTreatmentDiskon ?>
                                        </span>
                                        <small class="d-inline-block text-decoration-line-through">Rp<?= $booking->bdTreatmentHarga ?></small>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?= form_open() ?>
                            <div class="mb-3">
                                <label for="testiJudul" class="form-label">Judul Testimoni</label>
                                <input type="text" class="form-control <?= form_error('testiJudul') ? 'is-invalid' : null ?>" id="testiJudul" name="testiJudul" placeholder="perawatan terbaik, bagus banget, dsb." value="<?= set_value('testiJudul') ?>">
                                <?= form_error('testiJudul', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                            <div class="mb-3">
                                <label for="testiTeks" class="form-label">Ulasan</label>
                                <textarea class="form-control <?= form_error('testiTeks') ? 'is-invalid' : null ?>" id="testiTeks" name="testiTeks" rows="3"><?= set_value('testiTeks') ?></textarea>
                                <?= form_error('testiTeks', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                            <button type="submit" class="btn btn-primary waitme"><i class="fas fa-check-circle waitme"></i> Kirim Ulasan</button>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>