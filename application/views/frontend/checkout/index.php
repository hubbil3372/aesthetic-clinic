<!-- Checkout -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center mb-3">
        <div class="col-md-9">
            <h3 class="fw-bold text-center">Lanjut Pembayaran</h3>
        </div>
    </div>
    <?= form_open() ?>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow px-3 py-2 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3 pb-2 border-bottom d-flex justify-content-between">
                                    <h6 class="fs-5 mb-0 fw-bold">Informasi Pengiriman</h6>
                                    <a class="btn btn-sm btn-primary" href="<?= base_url('profil') ?>">
                                        <i class="fas fa-edit"></i> Ubah
                                    </a>
                                </div>
                                <div class="col-md-12 mb-3 pb-3 border-bottom">
                                    <b class="d-block">Nama Penerima</b>
                                    <span class="d-block"><?= $alamat->customerAlamatPenerima ?></span>
                                    <small class="mb-3"><?= $alamat->customerAlamatNoHp ?></small>
                                </div>
                                <div class="col-md-12 mb-3 pb-3">
                                    <b class="d-block">Alamat Lengkap</b>
                                    <span class="d-block"><?= $alamat->customerAlamatLengkap ?></span>
                                    <small class="mb-3">
                                        <?= $alamat->customerAlamatKecamatanNama ?>, <?= $alamat->customerAlamatKotkabNama ?>, Provinsi <?= $alamat->customerAlamatProvinsiNama ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow px-3 py-2 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3 pb-2 border-bottom d-flex justify-content-between">
                                    <h6 class="fs-5 mb-0 fw-bold">Detail Produk</h6>
                                </div>
                                <?php foreach ($c_detail as $key => $v) { ?>
                                    <div class="col-md-9">
                                        <img class="float-start" src="<?= base_url('_uploads/produk/' . $v->detailProdukGambar) ?>" width="100px">
                                        <span class="d-block mt-4">
                                            <?= $v->detailProdukNama ?>
                                        </span>
                                        <small class="d-block text-secondary">Jumlah: <?= $v->detailKuantitas ?></small>
                                        <small class="d-block text-secondary">Berat: <?= $v->detailBerat ?> gram</small>
                                    </div>
                                    <div class="col-3 text-end">
                                        <span class="d-block fw-bold mt-4">
                                            Rp<?= $v->detailTotalHarga ?>
                                        </span>
                                        <?php if ($v->detailProdukDiskon != 0) { ?>
                                            <span class="small text-decoration-line-through text-secondary">
                                                Rp<?= $v->detailProdukHarga ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow p-1 mb-3 bg-body rounded border-0">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <b class="mb-3 fs-5">Pilih Kurir</b>
                                </div>
                                <div class="col-md-12">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($ongkir as $key => $v) { ?>
                                            <?php if (!$v->costs) continue; ?>
                                            <li class="list-group-item">
                                                <b>
                                                    <?= $v->name ?>
                                                </b>
                                                <?php foreach ($v->costs as $key2 => $c) { ?>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="ongkoskirim" id="<?= $v->code . $c->service ?>" value="<?= $v->code . '-' . $c->service . '-' . $c->cost[0]->value ?>" onclick="update_tagihan(<?= $c->cost[0]->value ?>)">
                                                        <label class="form-check-label" for="<?= $v->code . $c->service ?>">
                                                            <?= $c->description ?>
                                                        </label>
                                                        <span class="float-end">Rp<?= $c->cost[0]->value ?></span>
                                                    </div>
                                                <?php } ?>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                            <b class="mb-3 fs-5">Catatan</b>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="checkoutCatatan" class="form-label">Catatan Pengiriman</label>
                                <textarea class="form-control" id="checkoutCatatan" name="checkoutCatatan" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                            <b class="mb-3 fs-5">Voucher</b>
                        </div>
                        <div class="col-md-12">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control checkoutvoucherpotongan" aria-label="Voucher" aria-describedby="checkoutvoucherpotongan" name="checkoutvoucherpotongan">
                                <span class="input-group-text" id="checkoutvoucherpotongan" style="cursor: pointer" onclick="cek_voucher()">Pakai</span>
                                <div id="invalid" class="invalid-feedback d-none">
                                    Voucher tidak ditemukan atau sudah tidak aktif!
                                </div>
                                <div id="valid" class="valid-feedback d-none">
                                    Voucher berhasil dipakai!
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                            <b class="mb-3 fs-5 text-primary">Total Tagihan</b>
                            <b class="fs-5 text-primary float-end">
                                Rp<span id="total_tagihan"><?= $total_tagihan ?></span>
                            </b>
                        </div>
                        <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                            <button type="submit" class="btn btn-lg btn-primary w-100 waitme">
                                <i class="fas fa-cash-register waitme"></i> Bayar Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?= form_close() ?>
</div>
<!-- /Checkout -->

<script>
    let total_tagihan = <?= $total_tagihan ?>;
    let potongan_harga = 0;

    function update_tagihan(ongkir) {
        let cost = total_tagihan + ongkir - potongan_harga;
        if (cost < 0) cost = 0;

        $('#total_tagihan').html(cost);
    }

    $(".checkoutvoucherpotongan").on("input", function() {
        if (!$(this).val()) {
            $('#invalid').addClass('d-none');
            $('#valid').addClass('d-none');
            $('.checkoutvoucherpotongan').removeClass('is-invalid');
            $('.checkoutvoucherpotongan').removeClass('is-valid');
            potongan_harga = 0;
            $('input[name="ongkoskirim"]').trigger("click");
        }
    });

    function cek_voucher() {
        var kode = $('.checkoutvoucherpotongan').val();

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '<?= base_url('Checkout/voucher/') ?>' + kode,
            success: function(response) {
                if (response.status == false) {
                    $('#invalid').removeClass('d-none');
                    $('#valid').addClass('d-none');
                    $('.checkoutvoucherpotongan').addClass('is-invalid');
                    $('.checkoutvoucherpotongan').removeClass('is-valid');
                    potongan_harga = 0;
                    $('input[name="ongkoskirim"]').trigger("click");
                } else {
                    $('#valid').removeClass('d-none');
                    $('#invalid').addClass('d-none');
                    $('.checkoutvoucherpotongan').addClass('is-valid');
                    $('.checkoutvoucherpotongan').removeClass('is-invalid');
                    $('#valid').html('Voucher berhasil dipakai! Anda mendapatkan potongan harga Rp' + response.data.voucherPotongan);
                    potongan_harga = parseInt(response.data.voucherPotongan);
                    $('input[name="ongkoskirim"]').trigger("click");
                }
            }
        });
    };
</script>