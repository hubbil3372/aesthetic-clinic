<!-- treatment -->
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="ratio ratio-4x3 image-slider" style="background-image:url('<?= base_url('_uploads/treatment/' . $treatment->treatmentFoto) ?>');"></div>
        </div>
        <div class="col-md-6">
            <h3 class="fw-bold mb-3"><?= $treatment->treatmentNama ?></h3>
            <span class="d-block h1 fw-bold mt-2 mb-3">
                Rp<?= $treatment->treatmentHarga - $treatment->treatmentDiskon ?>
            </span>
            <?php if ($treatment->treatmentDiskon != 0) { ?>
                <span class="h5 text-decoration-line-through text-secondary">
                    Rp<?= $treatment->treatmentHarga ?>
                </span>
                <span class="h5 text-danger ms-2">
                    <?= round($treatment->treatmentDiskon / $treatment->treatmentHarga * 100) . '%' ?>
                </span>
            <?php } ?>
            <?= form_open(base_url('booking-treatment'), array('method' => 'get')) ?>
            <div class="my-3">
                <!-- <label class="form-label me-3 h5">JUMLAH</label> -->
                <input class="d-none" name="id" value="<?= $treatment->treatmentId ?>">
            </div>
            <div class="mt-3">
                <button class="btn btn-lg btn-primary px-5" type="submit">
                    <i class="fa fa-cart-plus me-2" aria-hidden="true"></i> BOOKING SEKARANG
                </button>
            </div>
            <?= form_close() ?>
        </div>
        <div class="col-md-12 py-5">
            <h3 class="mb-3">Deskripsi treatment</h3>
            <p>
                <?= $treatment->treatmentDeskripsi ?>
            </p>
        </div>
        <div class="col-md-12 py-5">
            <h3 class="mb-3">Ulasan</h3>
            <div class="row">
                <?php if (!$testimoni) echo ('Belum ada ulasan') ?>
                <?php foreach ($testimoni as $key => $v) { ?>
                    <div class="col-md-12">
                        <span class="d-block mt-3">
                            <?= date('d F Y', strtotime(($v->testiDibuatPada))) ?>
                        </span>
                        <span class="d-block fst-italic fw-bold mt-1 ">
                            "<?= $v->testiTeks ?>"
                        </span>
                        <span class="d-block fst-italic small <?= $v->testiBalasan ? null : 'd-none' ?>">
                            [Admin] "<?= $v->testiBalasan ?>"
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12">
            <h2>treatment Terkait</h2>
            <div class="owl-carousel owl-theme" id="terkait">
                <?php foreach ($terkait as $key => $v) { ?>
                    <div class="item">
                        <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                            <div class="card-body text-center">
                                <a class="text-decoration-none text-dark" href="<?= base_url('treatment/' . $v->treatmentId . '/lihat') ?>">
                                    <center>
                                        <img class="img img-fluid w-75" src="<?= base_url('_uploads/treatment/' . $v->treatmentFoto) ?>">
                                    </center>
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
                                <a class="small text-decoration-none" href="<?= base_url('keranjang/' . $v->treatmentId . '/tambah') ?>">Tambah ke <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- /treatment -->