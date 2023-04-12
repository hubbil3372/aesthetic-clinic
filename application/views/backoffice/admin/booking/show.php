<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center w-100">
                    <h3 class="card-title"><?= $title; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-body pb-0">
                                <div class="table-resposive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Kode Booking</td>
                                            <td class="fw-bold "><?= $booking->bookingKode ?></td>
                                        </tr>
                                        <tr>
                                            <td>Status </td>
                                            <td class="fw-bold "><?= status_booking($booking->bookingStatus) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Status Pembayaran</td>
                                            <td class="fw-bold "><?= status_bayar($booking->bookingStatusBayar) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Pemesanan</td>
                                            <td class="fw-bold "><?= indo_date($booking->bookingDibuatPada) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-body pb-0">
                                <div class="table-resposive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Customer</td>
                                            <td class="fw-bold "><?= $booking->customerNama ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td class="fw-bold "><?= $booking->customerEmail ?></td>
                                        </tr>
                                        <tr>
                                            <td>Telepon</td>
                                            <td class="fw-bold "><?= $booking->customerNoHp ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card card-body pb-0">
                                <div class="table-resposive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Treatment</td>
                                            <td class="fw-bold "><?= $booking->bdTreatmentNama ?></td>
                                        </tr>
                                        <tr>
                                            <td>Detail</td>
                                            <td class="fw-bold "><?= $booking->bdTreatmentDeskripsi ?></td>
                                        </tr>
                                        <!-- <tr>
                                            <td>Harga</td>
                                            <td class="fw-bold ">Rp<?= $booking->bdTreatmentHarga ?></td>
                                        </tr>
                                        <tr>
                                            <td>Potongan Diskon Produk</td>
                                            <td class="fw-bold ">Rp<?= $booking->bdTreatmentDiskon ?></td>
                                        </tr> -->
                                        <tr>
                                            <td>Nama Dokter</td>
                                            <td class="fw-bold "><?= $booking->dokterNama ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Treatment</td>
                                            <td class="fw-bold "><?= indo_date($booking->bookingTgl) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Mulai</td>
                                            <td class="fw-bold "><?= substr($booking->bookingJamAwal, 0, 5) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Selesai</td>
                                            <td class="fw-bold "><?= substr($booking->bookingJamAkhir, 0, 5) ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-body pb-0">
                                <div class="table-resposive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td>Harga Treatment</td>
                                            <td class="fw-bold text-end">Rp<?= $booking->bdTreatmentHarga ?></td>
                                        </tr>
                                        <tr>
                                            <td>Diskon</td>
                                            <td class="fw-bold text-end">-Rp<?= $booking->bdTreatmentDiskon ?></td>
                                        </tr>
                                        <tr>
                                            <td>Potongan Voucher</td>
                                            <td class="fw-bold text-end">-Rp<?= $booking->bookingVoucherPotongan == null ? 0 : $booking->bookingVoucherPotongan; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Harga Net</td>
                                            <td class="fw-bold text-end">Rp<?= $booking->bookingHarga ?></td>
                                        </tr>
                                        <tr>
                                            <td>Uang Muka</td>
                                            <td class="fw-bold text-end">Rp<?= $booking->bookingDp ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex">
                            <div class="card card-body pb-0 justify-content-center">
                                <div class="text-center">
                                    <p class="mb-0 fs-4">Sisa Pembayaran</p>
                                    <h3 class="fw-bold display-3" id="sisa-bayar">Rp<?= $booking->bookingHarga - $booking->bookingDp ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <?php if ($booking->bookingBuktiBayar != null) : ?>
                                                <a href="<?= base_url('_uploads/bukti_bayar/' . $booking->bookingBuktiBayar) ?>" target="_blank" class="btn btn-outline-dark">
                                                    Lihat Bukti Bayar
                                                </a>
                                                <!-- <a href="<?= site_url("booking/{$booking->bookingId}/ubah-data-bayar") ?>" class="btn btn-outline-success waitme">
                                                    Ubah Data</a> -->
                                                <?= form_open('backoffice/booking/status-bayar', ['method' => 'POST']) ?>
                                                <div class="my-3">
                                                    <input type="hidden" name="bookingId" id="bookingId" value="<?= $booking->bookingId ?>">
                                                    <label for="" class="form-label">Ubah Status Pembayaran</label>
                                                    <select class="form-select <?= form_error('bookingStatusBayar') ? 'is-invalid' : null ?>" aria-label="Default select example" name="bookingStatusBayar">
                                                        <option value="">Pilih</option>
                                                        <option value="dp" <?= set_value('bookingStatusBayar') == 'dp' ? 'selected' : null ?>>Konfirmasi DP</option>
                                                        <option value="tolak" <?= set_value('bookingStatusBayar') == 'tolak' ? 'selected' : null ?>>Tolak</option>
                                                        <!-- <option value="lunas" <?= set_value('bookingStatusBayar') == 'lunas' ? 'selected' : null ?>>Lunas</option> -->
                                                    </select>
                                                    <?= form_error('bookingStatusBayar', '<div class="invalid-feedback">', '</div>') ?>
                                                </div>
                                                <div class="my-3">
                                                    <button type="submit" class="btn btn-primary float-end">Proses</button>
                                                </div>
                                                <?= form_close() ?>
                                            <?php elseif ($booking->bookingBuktiBayar == null) : ?>
                                                <button type="button" class="btn btn-outline-danger waitme">Bukti Bayar Belum Dikirim</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($booking->bookingStatusBayar == 'dp') : ?>
                            <div class="col-md-6">
                                <div class="card card-body">
                                    <form action="" method="post">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label class="form-label" for="bookingTunai">Tunai</label>
                                                    <input class="form-control <?= form_error('bookingTunai') ? 'is-invalid' : null; ?>" id="bookingTunai" name="bookingTunai" type="number" value="<?= set_value('bookingTunai'); ?>">
                                                    <div class="invalid-feedback">
                                                        <?= form_error('bookingTunai') ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-4">
                                                    <label class="form-label" for="bookingKembali">Kembali</label>
                                                    <input class="form-control <?= form_error('bookingKembali') ? 'is-invalid' : null; ?>" id="bookingKembali" name="bookingKembali" type="number" value="<?= set_value('bookingKembali'); ?>">
                                                    <div class="invalid-feedback">
                                                        <?= form_error('bookingKembali') ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button class="btn btn-outline-success waitme float-end" type="submit">Proses Pembayaran</button>
                                    </form>
                                </div>
                            </div>
                        <?php endif;
                        if ($booking->bookingStatusBayar == 'lunas') : ?>
                            <div class="col-md-6">
                                <div class="card card-body justify-content-center">
                                    <a href="<?= site_url("backoffice/booking/{$booking->bookingId}/batalkan") ?>" class="btn btn-outline-warning btn-lg confirm" type="submit">Batalkan Pelunasan</a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let tunai = document.getElementById("bookingTunai");
        let kembali = document.getElementById("bookingKembali");
        let sisaBayar = document.getElementById("sisa-bayar");
        // var kembaliInt = 0;

        tunai.addEventListener("keyup", function(e) {
            let tunaiInt = parseInt(e.target.value.replace(/[^,\d]/g, ""))
            let sisaBayarInt = parseInt(sisaBayar.textContent.replace(/[^,\d]/g, ""))
            let kembaliInt = tunaiInt - sisaBayarInt;
            if (tunaiInt <= sisaBayarInt) {
                kembali.value = 0;
            } else {
                kembali.value = kembaliInt;
            }
        })
    })

    function formatRupiah(angka, prefix) {
        let number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }

        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
        return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
    }
</script>