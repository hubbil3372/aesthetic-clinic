<div class="container mt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9">
            <h3 class="fw-bold text-center">Booking</h3>
        </div>
        <?php if ($booking) : ?>
            <div class="col-md-9">
                <?php foreach ($booking as $key => $v) : ?>
                    <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <img class="float-start me-3" src="<?= base_url('_uploads/treatment/' . $v->bdTreatmentFoto) ?>" width="100px">
                                    <span class="d-block mt-4">
                                        <?= $v->bdTreatmentNama ?>
                                    </span>
                                    <span class="h4 fw-bold mt-2">
                                        Rp<?= $v->bdTreatmentHarga - $v->bdTreatmentDiskon ?>
                                    </span>
                                    <?php if ($v->bdTreatmentDiskon != 0) { ?>
                                        <span class="small text-decoration-line-through text-secondary">
                                            Rp<?= $v->bdTreatmentHarga ?>
                                        </span>
                                        <span class="small text-danger ms-2">
                                            <?= round($v->bdTreatmentDiskon / $v->bdTreatmentHarga * 100) . '%' ?>
                                        </span>
                                    <?php } ?>
                                    <br>
                                </div>
                                <div class="col-md-3">
                                    <a class="btn btn-sm btn-outline-primary mt-3 w-100" href="<?= base_url('booking-treatment/' . $v->bookingId . '/lihat') ?>">
                                        <i class="fa fa-check-circle" aria-hidden="true"></i>
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-9">
                <div class="d-grid">
                    <a href="<?= site_url('treatment') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Booking Sekarang </a>
                </div>
            </div>
        <?php else : ?>
            <div class="col-md-9 text-center mt-4">
                Data Booking tidak ada
            </div>

            <div class="col-md-8 text-center mt-4 d-grid">
                <a href="<?= site_url('treatment') ?>" class="btn btn-primary waitme"><i class="fas fa-plus"></i> Booking Sekarang</a>
            </div>
        <?php endif; ?>
    </div>
</div>