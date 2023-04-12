<!-- Banner Promo -->
<div class="container-fluid p-0">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($voucher as $key => $v) : ?>
                <div class="carousel-item <?= $key == 0 ? 'active' : null ?>">
                    <div class="image-slider ratio ratio-21x9" style="background-image: url('<?= base_url('_uploads/voucher/' . $v->voucherGambar) ?>') ;">
                    </div>
                    <div class="carousel-caption d-none d-md-block">
                        <a href="<?= site_url("voucher/{$v->voucherId}/lihat") ?>" class="shadow rounded text-decoration-none btn btn-lg btn-light">
                            <h5 class="mb-0 fw-bold"><?= $v->voucherNama ?></h5>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!-- /Banner Promo -->
<!-- Produk Terbaru -->
<div class="container mt-5 pt-5">
    <!-- content lorem ipsum -->
    <div class="row mb-5 justify-content-between">
        <div class="col-md-6">
            <h2 class="display-4 text-start">Klinik Kecantikan Terbaik Untuk Anda</h2>
            <p class="text-muted fs-5">Untuk Kamu yang Mendambakan kulit putih dan bercahaya perawatan yang sesuai hanya ada di klinik kecantikan kami, ayo booking sekarang untuk mendapatkan promo khusus.</p>
        </div>
        <div class="col-md-4">
            <img src="<?= base_url() ?>_assets/images/logo-clinic.png" class="img-fluid" alt="image lorem">
        </div>
    </div>
    <!-- end content lorem ipsum -->
    <div class="row">
        <div class="col-md-12 text-center mb-5 pb-3">
            <h1 class="fw-bold">Produk Kecantikan</h1>
        </div>
        <?php foreach ($produk as $key => $v) { ?>
            <div class="col-6 col-lg-3">
                <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                    <div class="card-body text-center">
                        <a class="text-decoration-none text-dark" href="<?= base_url('produk/' . $v->produkId . '/lihat') ?>">
                            <img class="img img-fluid w-75" src="<?= base_url('_uploads/produk/' . $v->produkGambar1) ?>">
                            <span class="d-block mt-4">
                                <?= $v->produkNama ?>
                            </span>
                            <span class="d-block h4 fw-bold mt-2">
                                Rp<?= $v->produkHarga - $v->produkDiskon ?>
                            </span>
                            <?php if ($v->produkDiskon != 0) { ?>
                                <span class="text-decoration-line-through text-secondary">
                                    Rp<?= $v->produkHarga ?>
                                </span>
                                <span class="text-danger ms-2">
                                    <?= round($v->produkDiskon / $v->produkHarga * 100) . '%' ?>
                                </span>
                            <?php } ?>
                        </a>
                    </div>
                    <div class="card-footer text-center">
                        <a class="text-decoration-none" href="<?= base_url('keranjang?id=' . $v->produkId . '&qty=1') ?>">Tambah ke <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="col-md-12 text-center mb-5 pb-3">
            <a class="btn btn-lg btn-primary px-3 w-50" href="<?= base_url('produk') ?>">Produk lainnya...</a>
        </div>
    </div>
</div>
<!-- /Produk Terbaru -->