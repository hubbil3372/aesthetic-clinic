<div class="container mt-5 min-vh-100">
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12 text-center mb-5 pb-3">
                    <h1 class="fw-bold"><?= $title ?></h1>
                </div>
                <div class="col-12">
                    <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <img src="<?= base_url("_uploads/voucher/{$voucher->voucherGambar}") ?>" alt="" class="img-fluid w-100">
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 border-bottom">
                                            <p class="fs-6 mb-0 fw-bold">Nama Voucher</p>
                                            <h3><?= $voucher->voucherNama ?></h3>
                                        </div>
                                        <div class="col-md-12 border-bottom">
                                            <p class="fs-6 mb-0 fw-bold">Kode Voucher</p>
                                            <p class="mb-0 fs-4"><?= $voucher->voucherKode ?></p>
                                        </div>
                                        <div class="col-md-12 border-bottom">
                                            <p class="fs-6 mb-0 fw-bold">Status Voucher</p>
                                            <p class="mb-0 fs-5"><?= $voucher->voucherStatus == 1 ? 'Aktif' : 'Tidak Aktif'; ?></p>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>