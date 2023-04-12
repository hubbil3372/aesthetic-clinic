<!-- Transaksi -->
<div class="container mt-5 pt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9 mb-3">
            <h3 class="fw-bold text-center">Transaksi</h3>
        </div>
        <?php if ($transaksi) { ?>
            <?php foreach ($transaksi as $key => $v) { ?>
                <div class="col-md-9">
                    <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <b class="small mb-3">Kode Transaksi: <?= $v->checkoutKode ?></b>
                                </div>
                                <div class="col-md-8">
                                    <img class="float-start" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
                                    <span class="d-block mt-4">
                                        <?= $v->detailProdukNama ?>
                                    </span>
                                    <small>Total </small>
                                    <span class="h4 fw-bold mt-2">
                                        Rp<?= $v->checkoutTotalTagihan ?>
                                    </span>
                                </div>
                                <div class="col-md-4">
                                    <small>
                                        Status Pesanan<br>
                                        <?php if ($v->checkoutStatusBayar == 0) { ?>
                                            <b class="text-warning">Menunggu pembayaran</b>
                                        <?php } ?>
                                        <?php if ($v->checkoutStatusBayar == 1 and $v->checkoutStatusPengiriman == 0) { ?>
                                            <b class="text-success">Sudah dibayar</b>
                                        <?php } ?>
                                        <?php if ($v->checkoutStatusBayar == 1 and $v->checkoutStatusPengiriman == 1) { ?>
                                            <b class="text-info">Sedang dikirim</b>
                                        <?php } ?>
                                        <?php if ($v->checkoutStatusBayar == 1 and $v->checkoutStatusPengiriman == 2) { ?>
                                            <b class="text-success">Pesanan sudah sampai</b>
                                        <?php } ?>
                                        <?php if ($v->checkoutStatusBayar == 2) { ?>
                                            <b class="text-danger">Bukti pembayaran ditolak</b>
                                        <?php } ?>
                                    </small>
                                    <a class="btn btn-sm btn-primary mt-3 w-100 waitme" href="<?= base_url('transaksi/' . $v->checkoutId . '/detail') ?>">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        Lihat Transaksi
                                    </a>
                                    <?php if ($v->checkoutStatusPengiriman == 2) { ?>
                                        <!-- <a class="btn btn-sm btn-outline-success mt-1 w-100" href="<?= base_url('testimoni/' . $v->checkoutId . '/create') ?>">
                                            <i class="fa fa-commenting" aria-hidden="true"></i>
                                            Beri Ulasan
                                        </a> -->
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="col-md-9 text-center mt-4">
                Tidak ada transaksi
            </div>
        <?php } ?>
    </div>
</div>
<!-- /Transaksi -->