<div class="container mt-5 min-vh-100">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center mb-5 pb-3">
                    <h1 class="fw-bold"><?= $title ?></h1>
                </div>
                <?php if ($treatment->num_rows() < 1) { ?>
                    <div class="col-12 col-lg-12 text-center">
                        <center>treatment tidak ditemukan</center>
                    </div>
                <?php } ?>
                <?php foreach ($treatment->result() as $key => $v) { ?>
                    <div class="col-6 col-lg-3">
                        <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                            <div class="card-body text-center">
                                <a class="text-decoration-none text-dark waitme" href="<?= base_url('treatment/' . $v->treatmentId . '/lihat') ?>">
                                    <img class="img img-fluid w-75" src="<?= base_url('_uploads/treatment/' . $v->treatmentFoto) ?>">
                                    <span class="small d-block mt-4">
                                        <?= $v->treatmentNama ?>
                                    </span>
                                    <span class="d-block h5 fw-bold mt-2">
                                        Rp<?= $v->treatmentHarga - $v->treatmentDiskon ?>
                                    </span>
                                    <?php if ($v->treatmentDiskon != 0) { ?>
                                        <span class="small text-decoration-line-through text-secondary">
                                            Rp<?= $v->treatmentHarga ?>
                                        </span>
                                        <span class="small text-danger ms-2">
                                            <?= round($v->treatmentDiskon / $v->treatmentHarga * 100) . '%' ?>
                                        </span>
                                    <?php } ?>
                                </a>
                            </div>
                            <div class="card-footer text-center">
                                <a class="text-decoration-none waitme" href="<?= base_url("booking-treatment/{$v->treatmentId}/tambah") ?>">Booking Sekarang</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12 text-center my-5 pb-3">
            <?= $this->pagination->create_links() ?>
        </div>
    </div>
</div>