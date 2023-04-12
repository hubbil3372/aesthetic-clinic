<!-- Transaksi -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9">
            <h3 class="fw-bold text-center">Transaksi</h3>
        </div>
        <?php foreach ($produk as $key => $v) { ?>
            <div class="col-md-9">
                <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <b class="small mb-3">Kode Transaksi: BL2217SITZWZINV</b>
                            </div>
                            <div class="col-md-9">
                                <img class="float-start" src="<?= base_url('_uploads/produk/' . $v->produkGambar1) ?>" width="100px">
                                <span class="d-block mt-4">
                                    <?= $v->produkNama ?>
                                </span>
                                <small>Total </small>
                                <span class="h4 fw-bold mt-2">
                                    Rp<?= $v->produkHarga - $v->produkDiskon ?>
                                </span>
                            </div>
                            <div class="col-md-3">
                                <small>
                                    Status : <b class="text-success">Selesai</b>
                                </small>
                                <a class="btn btn-sm btn-primary mt-3 w-100" href="<?= base_url('transaksi/123/detail') ?>">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                    Lihat Transaksi
                                </a>
                                <a class="btn btn-sm btn-outline-success mt-1 w-100" href="<?= base_url('transaksi/ulasan') ?>">
                                    <i class="fa fa-commenting" aria-hidden="true"></i>
                                    Beri Ulasan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<!-- /Transaksi -->