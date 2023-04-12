<!-- Produk -->
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-6">
            <div class="owl-carousel owl-theme" id="produk">
                <?php if ($produk->produkGambar1 != 'default.png') : ?>
                    <div class="item">
                        <img class="img img-fluid w-100 ratio ratio-4x3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar1) ?>">
                    </div>
                <?php endif ?>
                <?php if ($produk->produkGambar2 != 'default.png') : ?>
                    <div class="item">
                        <img class="img img-fluid w-100 ratio ratio-4x3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar2) ?>">
                    </div>
                <?php endif ?>
                <?php if ($produk->produkGambar3 != 'default.png') : ?>
                    <div class="item">
                        <img class="img img-fluid w-100 ratio ratio-4x3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar3) ?>">
                    </div>
                <?php endif ?>
            </div>
        </div>
        <div class="col-md-6">
            <h3 class="fw-bold mb-3"><?= $produk->produkNama ?></h3>
            <span class="d-block h1 fw-bold mt-2 mb-3">
                Rp<?= $produk->produkHarga - $produk->produkDiskon ?>
            </span>
            <?php if ($produk->produkDiskon != 0) { ?>
                <span class="h5 text-decoration-line-through text-secondary">
                    Rp<?= $produk->produkHarga ?>
                </span>
                <span class="h5 text-danger ms-2">
                    <?= round($produk->produkDiskon / $produk->produkHarga * 100) . '%' ?>
                </span>
            <?php } ?>
            <?= form_open(base_url('keranjang'), array('method' => 'get')) ?>
            <div class="my-3">
                <label class="form-label h5">STOK: <?= $produk->produkStok ?></label>
                <label class="form-label mx-3 h5">|</label>
                <label class="form-label h5">BERAT: <?= $produk->produkBerat ?> gram</label>
            </div>
            <div class="my-3">
                <label class="form-label me-3 h5">JUMLAH</label>
                <input type="number" class="form-control d-inline w-25" name="qty" min="1" max="<?= $produk->produkStok ?>" placeholder="1" value="1">
                <input class="d-none" name="id" value="<?= $produk->produkId ?>">
            </div>
            <div class="mt-3">
                <button class="btn btn-lg btn-primary px-5" type="submit">
                    <i class="fa fa-cart-plus me-2" aria-hidden="true"></i> MASUKAN KERANJANG
                </button>
            </div>
            <?= form_close() ?>
        </div>
        <div class="col-md-12 py-5">
            <h3 class="mb-3">Deskripsi Produk</h3>
            <p>
                <?= $produk->produkDeskripsi ?>
            </p>
        </div>
        <div class="col-md-12 py-5">
            <h3 class="mb-3">Ulasan</h3>
            <div class="row">
                <?php if (!$testimoni) echo ('Belum ada ulasan') ?>
                <?php foreach ($testimoni as $key => $v) { ?>
                    <div class="col-md-12">
                        <span class="d-block mt-3">
                            <?= date('d F Y', strtotime(($v->testimoniDibuatPada))) ?>
                        </span>
                        <span class="d-block fst-italic fw-bold mt-1 ">
                            "<?= $v->testimoniTeks ?>"
                        </span>
                        <span class="d-block fst-italic small <?= $v->testimoniBalasan ? null : 'd-none' ?>">
                            [Admin] "<?= $v->testimoniBalasan ?>"
                        </span>
                    </div>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-12">
            <h2>Produk Terkait</h2>
            <div class="owl-carousel owl-theme" id="terkait">
                <?php foreach ($terkait as $key => $v) { ?>
                    <div class="item">
                        <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                            <div class="card-body text-center">
                                <a class="text-decoration-none text-dark" href="<?= base_url('produk/' . $v->produkId . '/lihat') ?>">
                                    <center>
                                        <img class="img img-fluid w-75" src="<?= base_url('_uploads/produk/' . $v->produkGambar1) ?>">
                                    </center>
                                    <span class="small d-block mt-4">
                                        <?= $v->produkNama ?>
                                    </span>
                                    <span class="d-block h5 fw-bold mt-2">
                                        Rp<?= $v->produkHarga - $v->produkDiskon ?>
                                    </span>
                                    <?php if ($v->produkDiskon != 0) { ?>
                                        <span class="small text-decoration-line-through text-secondary">
                                            Rp<?= $v->produkHarga ?>
                                        </span>
                                        <span class="small text-danger ms-2">
                                            <?= round($v->produkDiskon / $v->produkHarga * 100) . '%' ?>
                                        </span>
                                    <?php } ?>
                                </a>
                            </div>
                            <div class="card-footer text-center">
                                <a class="small text-decoration-none" href="<?= base_url('keranjang/' . $v->produkId . '/tambah') ?>">Tambah ke <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<!-- /Produk -->