<div class="container mt-5 min-vh-100">
    <div class="row mb-5 justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between mb-3">
                <h4 class="text-capitalize fw-bold"><?= $title ?></h4>
                <a href="<?= site_url('konsultasi/tambah') ?>" class="btn btn-outline-primary waitme"> <i class="fas fa-plus"></i> Buat konsultasi</a>
            </div>
            <?php if (!$konsultasi) : ?>
                <div class="card card-body rounded mb-4">
                    <p class="fw-bold mb-0">Anda Belum membuat konsultasi, buat konsultasi sekarang!</p>
                </div>
                <?php else :
                foreach ($konsultasi as $key => $value) : ?>
                    <div class="card rounded mb-4">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <h5 class="card-title mb-0 text-primary fw-bold"> <i class="fas fa-question-circle fw-bold"></i> <?= $value->konsultasiJudul ?></h5>
                            <a href="<?= site_url("konsultasi/{$value->konsultasiId}/detail") ?>" class="btn btn-sm px-5 btn-primary waitme">Detail</a>
                        </div>
                        <div class="card-body">
                            <div class="text-truncate">
                                <p><?= $value->konsultasiTeks ?></p>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end border-0 bg-white">
                            <small class="text-primary"><i class="fas fa-comment"></i> <?= $tanggapan ?> Tanggapan</small>
                        </div>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
        <div class="col-md-12 text-center my-5 pb-3">
            <?= $this->pagination->create_links() ?>
        </div>
    </div>
</div>