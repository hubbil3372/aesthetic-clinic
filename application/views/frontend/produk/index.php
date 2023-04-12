<!-- Produk Terbaru -->
<div class="container mt-5 pt-5">
    <div class="row">
        <div class="col-md-3">
            <h1 class="fw-bold">Kategori</h1>
            <div class="list-group list-group-flush mt-4">
                <a href="<?= base_url('produk') ?>" class="list-group-item list-group-item-action waitme <?= $this->input->get('kategori') == null ? 'active' : null ?>" aria-current="true">
                    Semua Kategori
                </a>
                <?php
                    $get = $this->input->get(null, true);
                    unset($get['kategori']);
                    unset($get['per_page']);
                    $uri = http_build_query($get);
                ?>
                <?php foreach ($kategori as $key => $v) { ?>
                    <a href="<?= base_url('produk?kategori=' . $v->kategoriId . '&' . $uri) ?>" class="list-group-item list-group-item-action waitme <?= $v->kategoriId == $this->input->get('kategori') ? 'active' : null ?>" aria-current="true">
                        <?= $v->kategoriNama ?>
                    </a>
                <?php } ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 text-center mb-5 pb-3">
                    <h1 class="fw-bold">Produk</h1>
                </div>
                <?php if(!$produk){ ?>
                    <div class="col-12 col-lg-12 text-center">
                        <center>Produk tidak ditemukan</center>
                    </div>
                <?php } ?>
                <?php foreach ($produk as $key => $v) { ?>
                    <div class="col-6 col-lg-3">
                        <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                            <div class="card-body text-center">
                                <a class="text-decoration-none text-dark waitme" href="<?= base_url('produk/' . $v->produkId . '/lihat') ?>">
                                    <img class="img img-fluid w-75" src="<?= base_url('_uploads/produk/' . $v->produkGambar1) ?>">
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
                                <a class="text-decoration-none waitme" href="<?= base_url('keranjang?id=' . $v->produkId . '&qty=1') ?>">Tambah ke <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>
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
<!-- /Produk Terbaru -->