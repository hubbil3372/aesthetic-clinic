<div class="container mt-5 min-vh-100">
    <div class="row mb-5 justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="">Kritik dan Saran</h4>
                <a href="<?= site_url('kritik-saran/buat') ?>" class="btn btn-outline-primary waitme"> <i class="fas fa-plus"></i> Buat Kritik dan Saran</a>
            </div>
            <?php if (count((array)$saran) < 1) : ?>
                <div class="card card-body rounded mb-4">
                    <p>Anda Belum membuat Saran, buat saran sekarang, agar kami menjadi lebih baik!</p>
                </div>
                <?php else :
                foreach ($saran as $key => $value) : ?>
                    <div class="card rounded mb-4">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <h5 class="card-title mb-0 text-primary fw-bold"> <i class="fas fa-question-circle fw-bold"></i> <?= $value->saranJudul ?></h5>
                            <a href="<?= site_url("kritik-saran/{$value->saranId}/detail") ?>" class="btn btn-sm px-5 btn-primary waitme">Detail</a>
                        </div>
                        <div class="card-body">
                            <div class="text-truncate">
                                <p><?= $value->saranText ?></p>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end border-0 bg-white">
                            <small class="text-primary"><i class="fas fa-comment"></i> <?= $value->tanggapan ?> Tanggapan</small>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
    </div>
</div>