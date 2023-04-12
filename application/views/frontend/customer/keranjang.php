<!-- Keranjang -->
<div class="container mt-5 pt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9">
            <h3 class="fw-bold text-center">Keranjang</h3>
        </div>
        <?php if ($keranjang) : ?>
            <div class="col-md-9">
                <?php foreach ($keranjang as $key => $v) : ?>
                    <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-9">
                                    <img class="float-start" src="<?= base_url('_uploads/produk/' . $v->produkGambar1) ?>" width="100px">
                                    <span class="d-block mt-4">
                                        <?= $v->produkNama ?>
                                    </span>
                                    <span class="h4 fw-bold mt-2">
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
                                    <br>
                                    <span class="small mt-2">
                                        Stok : <?= $v->produkStok ?>
                                    </span>
                                    <span class="small mt-2 mx-2">
                                        |
                                    </span>
                                    <span class="small mt-2">
                                        Berat : <?= $v->produkBerat ?> gram
                                    </span>
                                </div>
                                <div class="col-md-3">
                                    <div class="mt-3">
                                        <label class="form-label me-3">Jumlah</label>
                                        <input class="form-control d-none" value="<?= $v->keranjangProdukId ?>">
                                        <input type="number" class="form-control d-inline w-50 produkKuantitas waitme" min="1" max="<?= $v->produkStok ?>" placeholder="1" keranjangid="<?= $v->keranjangId ?>" value="<?= $v->keranjangKuantitas ?>">
                                    </div>
                                    <a class="btn btn-sm btn-outline-danger mt-3 w-100 destroy" href="<?= base_url('keranjang/' . $v->keranjangId . '/hapus') ?>">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                        Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="col-md-9 mb-5">
                <a href="<?= base_url('checkout') ?>" class="btn btn-lg btn-primary w-100 waitme">
                    <i class="fa fa-cart-arrow-down" aria-hidden="true"></i>
                    Lanjut Pembayaran
                </a>
            </div>
        <?php else : ?>
            <div class="col-md-9 text-center mt-4">
                Tidak ada produk pada keranjang
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- /Keranjang -->

<script>
    $('.produkKuantitas').change(function() {
        window.location = "<?= base_url('keranjang/') ?>" + $(this).attr("keranjangid") + "/update?qty=" + this.value;
    });
</script>