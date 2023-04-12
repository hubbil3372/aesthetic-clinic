<!-- Transaksi -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9 mb-3">
            <h3 class="fw-bold text-center">Detail Booking</h3>
        </div>
        <div class="col-md-4">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Status Booking</span>
                            <?php if ($booking->bookingStatus == 'pending') { ?>
                                <b class="mb-3 text-warning">Menunggu pembayaran</b>
                            <?php } ?>
                            <?php if ($booking->bookingStatus == 'konfirmasi') { ?>
                                <b class="mb-3 text-primary">Menunggu Konfirmasi</b>
                            <?php } ?>
                            <?php if ($booking->bookingStatus == 'diproses') { ?>
                                <b class="mb-3 text-info">Di Proses</b>
                            <?php } ?>
                            <?php if ($booking->bookingStatus == 'selesai') { ?>
                                <b class="mb-3 text-success">Treatment Selesai</b>
                            <?php } ?>
                        </div>
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Harga Treatment</span>
                            <b class="mb-3">Rp<?= $treatment->bdTreatmentHarga ?></b>
                        </div>
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Diskon Harga Treatment</span>
                            <b class="mb-3">(-)Rp<?= $treatment->bdTreatmentDiskon ?></b>
                        </div>
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Diskon Voucher Promo</span>
                            <b class="mb-3">(-)Rp<?= $booking->bookingVoucherPotongan ?></b>
                        </div>
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Total Tagihan</span>
                            <b class="mb-3">Rp<?= $booking->bookingHarga ?></b>
                        </div>
                        <div class="col-md-12 mb-3 pb-3 border-bottom">
                            <span class="d-block">Tagihan Uang Muka</span>
                            <b class="mb-3">Rp<?= $booking->bookingDp ?></b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card shadow p-3 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 pb-3 border-bottom">
                            <span class="d-block">Tanggal Transaksi</span>
                            <b class="mb-3"><?= indo_date($booking->bookingDibuatPada) ?></b>
                        </div>
                        <div class="col-md-6 mb-3 pb-3 border-bottom text-end">
                            <span class="d-block">Tagihan Uang Muka</span>
                            <b class="mb-3">Rp<?= $booking->bookingDp ?></b>
                        </div>

                        <div class="col-md-12 mt-3 p-3 bg-light border border-primary <?= in_array($booking->bookingStatusBayar, ['pending', 'tolak']) ? 'd-none' : null ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="mb-2"><?= $treatment->bdTreatmentNama ?></h4>
                                    <span class="d-block fw-bold">Kode Booking</span>
                                    <span class="h4 text-primary fw-bold"><?= $booking->bookingKode ?></span>
                                    <span class="d-block fw-bold mt-3">No Antrian</span>
                                    <span class="h2 bg-primary text-white"><?= $booking->bookingAntrian ?></span>
                                </div>
                                <div class="col-md-6">
                                    <span class="d-block fw-bold">Customer</span>
                                    <h6 class="mb-3"><?= $booking->customerNama ?></h6>
                                    <span class="d-block fw-bold">Dokter</span>
                                    <h6 class="mb-3"><?= $booking->dokterNama ?></h6>
                                    <span class="d-block fw-bold">Tanggal/Waktu</span>
                                    <h6 class="mb-3"><?= indo_date($booking->bookingTgl) . ", " . substr($booking->bookingJamAwal, 0, 5) . " s/d " . substr($booking->bookingJamAkhir, 0, 5) ?></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 mt-3 p-3 text-center bg-light border border-warning <?= !in_array($booking->bookingStatusBayar, ['pending', 'tolak']) ? 'd-none' : null ?>">
                            <span class="d-block fw-bold mb-3">Silahkan Bayar Tagihan ke</span>
                            <h4 class="mb-2">Bank Negara Indonesia</h4>
                            <span class="h2 bg-warning text-white">000123654233</span>
                            <h6 class="mt-2">a/n AESTHETIC CLINIC</h6>
                        </div>
                        <div class="col-md-12 mt-3 text-center <?= !in_array($booking->bookingStatusBayar, ['pending', 'tolak']) ? 'd-none' : null ?>">
                            <span class="d-block">Sudah membayar tagihan?</span>
                            <?= form_open_multipart() ?>
                            <div class="mb-3">
                                <label for="bookingBuktiBayar" class="form-label text-primary">Upload bukti pembayaran sekarang</label>
                                <center class="<?= $booking->bookingBuktiBayar == null ? 'd-none' : null ?>">
                                    <a href="<?= base_url('_uploads/bukti_bayar/' . $booking->bookingBuktiBayar) ?>" target="_blank" class="btn btn-outline-dark">
                                        Lihat Bukti Bayar
                                    </a>
                                </center>
                            </div>
                            <div class="input-group mb-3">
                                <input class="form-control text-primary <?= form_error('bookingBuktiBayar') ? 'is-invalid' : null ?> <?= in_array($booking->bookingStatusBayar, ['tolak']) ? 'is-invalid' : null ?>" type="file" id="bookingBuktiBayar" name="bookingBuktiBayar" accept="image/png, image/jpg, image/jpeg">
                                <button class="input-group-text btn-primary" type="submit">Upload</button>
                                <div class="invalid-feedback fw-bold">
                                    <?= form_error('bookingBuktiBayar') ?>
                                    <br>
                                    <?php if (in_array($booking->bookingStatusBayar, ['tolak'])) : ?>
                                        <h5>Pembayaran ditolak!</h5>
                                        <h6>Silahkan kirim bukti pembayaran yang lain.</h6>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- <div class="input-group mb-3">
                                <input class="form-control text-primary <?= $booking->bookingStatus == 'ditolak' ? 'is-invalid' : null ?>" type="file" id="bookingBuktiBayar" name="bookingBuktiBayar" accept="image/png, image/jpg, image/jpeg">
                                <button class="input-group-text btn-primary" type="submit">Upload</button>
                                <div class="invalid-feedback fw-bold <?= $booking->bookingStatus == 'ditolak' ? null : 'd-none' ?>">
                                    <br>
                                    <h5>Pembayaran ditolak!</h5>
                                    <h6>Silahkan kirim bukti pembayaran yang lain.</h6>
                                </div>
                            </div> -->
                            <?= form_close() ?>
                        </div>
                        <div class="col-md-12 mb-3 pt-4 mt-3">
                            <b class="mb-3 fs-5">Keluhan</b>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <textarea class="form-control bg-light" rows="2" readonly><?= $booking->bookingKeluhan ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                            <b class="mb-3 fs-5">Detail Treatment</b>
                        </div>
                        <div class="col-md-9">
                            <img class="float-start" src="<?= base_url('_uploads/treatment/' . $treatment->bdTreatmentFoto) ?>" width="100px">
                            <span class="d-inline-block ms-4">
                                <?= $treatment->bdTreatmentNama ?>
                                <span class="d-block fw-bold">
                                    Rp<?= $treatment->bdTreatmentHarga - $treatment->bdTreatmentDiskon ?>
                                </span>
                                <small class="d-inline-block text-decoration-line-through">Rp<?= $treatment->bdTreatmentHarga ?></small>
                            </span>
                        </div>
                        <?php if ($booking->bookingStatus == 'selesai') : ?>
                            <div class="col-md-12 mb-3 pt-4 mt-3 border-top">
                                <b class="mb-3 fs-5">Ulasan Kamu</b>
                            </div>
                            <div class="col-md-12 <?= count($testimoni) != 0 ? 'd-none' : null ?>">
                                <a href="<?= base_url('testimoni-treatment/' . $booking->bookingId . '/tambah') ?>" class="btn btn-primary w-100 waitme" type="button">
                                    <i class="fa fa-commenting" aria-hidden="true"></i> Beri ulasan
                                </a>
                            </div>
                            <?php
                            foreach ($testimoni as $key => $v) { ?>

                                <div class="col-md-12 <?= $v->testiTeks ? null : 'd-none' ?> mb-3">
                                    <div class="card mb-3">
                                        <div class="card-body d-flex">
                                            <?php if ($v->customerFoto == 'default.png') : ?>
                                                <div class="me-2 d-flex justify-content-center">
                                                    <svg class="align-self-center" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7936 0.603279C13.6813 0.804777 11.9191 1.30682 9.9536 2.2682C6.74248 3.83913 4.14711 6.4363 2.57841 9.64851C1.74168 11.3493 1.31136 12.7137 1.03302 14.5511C0.836648 15.8454 0.836648 17.968 1.03302 19.2794C1.36461 21.5394 2.16552 23.705 3.38424 25.6369C4.60296 27.5688 6.2125 29.2242 8.10937 30.4968C10.7968 32.3247 13.978 33.2891 17.228 33.2614C21.55 33.2614 25.5185 31.6596 28.5905 28.673C30.5525 26.7673 31.8384 24.7404 32.728 22.1517C33.3308 20.3979 33.5648 18.9106 33.5614 16.8546C33.5579 14.1447 33.0747 12.0819 31.8776 9.64851C30.5918 7.03415 28.6981 4.8911 26.2101 3.22959C25.4212 2.70194 23.7767 1.87716 22.8119 1.52368C20.6603 0.734765 18.0374 0.389827 15.7936 0.603279ZM18.9356 2.67803C23.1688 3.19031 26.8982 5.50755 29.2684 9.10207C30.404 10.8251 31.2339 13.1201 31.5003 15.2819C31.6044 16.1221 31.5874 17.886 31.4678 18.7996C31.1435 21.2566 30.1868 23.5873 28.6912 25.5635C28.0355 26.4309 27.8067 26.6768 27.7657 26.5624C27.6359 26.2158 26.7941 25.1178 26.2118 24.5389C24.7603 23.0908 22.7505 21.9826 20.5835 21.4345C18.63 20.9392 15.8961 20.9273 13.9836 21.4037C11.68 21.9792 9.6855 23.0755 8.17426 24.6004C7.65344 25.1263 6.81159 26.2397 6.69035 26.5624C6.64936 26.6751 6.42908 26.4395 5.7614 25.5635C4.61447 24.0596 3.78541 22.3381 3.32464 20.5038C3.02006 19.339 2.8719 18.1388 2.88408 16.9349C2.88408 15.2324 3.08387 13.9944 3.61493 12.3892C4.47097 9.81922 6.03683 7.54393 8.13152 5.82636C10.2262 4.10879 12.7642 3.01906 15.4521 2.68315C16.4118 2.56362 17.9708 2.56191 18.9356 2.67803ZM16.4169 8.45659C14.1424 8.86301 12.4348 10.6611 12.1786 12.9203C12.0935 13.632 12.1609 14.3537 12.3763 15.0374C12.5916 15.721 12.95 16.351 13.4276 16.8855C13.9053 17.42 14.4912 17.8467 15.1464 18.1373C15.8017 18.4278 16.5113 18.5756 17.228 18.5708C18.478 18.581 19.6873 18.1264 20.621 17.2953C21.5547 16.4641 22.1462 15.3157 22.2809 14.0729C22.4687 12.4165 21.871 10.8541 20.6057 9.69461C20.1054 9.23527 19.1781 8.73835 18.4917 8.55905C17.976 8.42415 16.8985 8.37121 16.4169 8.45659ZM18.6249 23.1421C19.5179 23.2445 20.1754 23.3897 20.9848 23.6629C23.1893 24.4091 25.0677 25.8623 26.0137 27.5562L26.2681 28.0088L26.0683 28.1727C25.6158 28.5415 24.289 29.3185 23.5069 29.672C21.4066 30.6214 19.5384 31.0244 17.228 31.0244C14.9176 31.0244 13.0495 30.6214 10.9491 29.672C10.167 29.3185 8.84023 28.5415 8.38771 28.1727L8.18792 28.0088L8.44236 27.5562C9.73161 25.2493 12.6226 23.5092 15.7714 23.1421C16.7209 23.0618 17.6754 23.0618 18.6249 23.1421Z" fill="black"></path>
                                                    </svg>
                                                </div>
                                            <?php else : ?>
                                                <img class="float-start me-2" src="<?= base_url('_uploads/treatment/' . $v->bdTreatmentFoto) ?>" width="100px">
                                            <?php endif; ?>
                                            <div class="customer-info">
                                                <span class="d-block">
                                                    <?= $v->customerNama ?>
                                                </span>
                                                <span class="d-block fst-italic fw-bold">
                                                    "<?= $v->testiTeks ?>"
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($v->testiBalasan != null) : ?>
                                        <p class="fw-bold">Balasan</p>
                                        <div class="card alert-primary">
                                            <div class="card-body d-flex">
                                                <div class="me-2 d-flex justify-content-center">
                                                    <svg class="align-self-center" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7936 0.603279C13.6813 0.804777 11.9191 1.30682 9.9536 2.2682C6.74248 3.83913 4.14711 6.4363 2.57841 9.64851C1.74168 11.3493 1.31136 12.7137 1.03302 14.5511C0.836648 15.8454 0.836648 17.968 1.03302 19.2794C1.36461 21.5394 2.16552 23.705 3.38424 25.6369C4.60296 27.5688 6.2125 29.2242 8.10937 30.4968C10.7968 32.3247 13.978 33.2891 17.228 33.2614C21.55 33.2614 25.5185 31.6596 28.5905 28.673C30.5525 26.7673 31.8384 24.7404 32.728 22.1517C33.3308 20.3979 33.5648 18.9106 33.5614 16.8546C33.5579 14.1447 33.0747 12.0819 31.8776 9.64851C30.5918 7.03415 28.6981 4.8911 26.2101 3.22959C25.4212 2.70194 23.7767 1.87716 22.8119 1.52368C20.6603 0.734765 18.0374 0.389827 15.7936 0.603279ZM18.9356 2.67803C23.1688 3.19031 26.8982 5.50755 29.2684 9.10207C30.404 10.8251 31.2339 13.1201 31.5003 15.2819C31.6044 16.1221 31.5874 17.886 31.4678 18.7996C31.1435 21.2566 30.1868 23.5873 28.6912 25.5635C28.0355 26.4309 27.8067 26.6768 27.7657 26.5624C27.6359 26.2158 26.7941 25.1178 26.2118 24.5389C24.7603 23.0908 22.7505 21.9826 20.5835 21.4345C18.63 20.9392 15.8961 20.9273 13.9836 21.4037C11.68 21.9792 9.6855 23.0755 8.17426 24.6004C7.65344 25.1263 6.81159 26.2397 6.69035 26.5624C6.64936 26.6751 6.42908 26.4395 5.7614 25.5635C4.61447 24.0596 3.78541 22.3381 3.32464 20.5038C3.02006 19.339 2.8719 18.1388 2.88408 16.9349C2.88408 15.2324 3.08387 13.9944 3.61493 12.3892C4.47097 9.81922 6.03683 7.54393 8.13152 5.82636C10.2262 4.10879 12.7642 3.01906 15.4521 2.68315C16.4118 2.56362 17.9708 2.56191 18.9356 2.67803ZM16.4169 8.45659C14.1424 8.86301 12.4348 10.6611 12.1786 12.9203C12.0935 13.632 12.1609 14.3537 12.3763 15.0374C12.5916 15.721 12.95 16.351 13.4276 16.8855C13.9053 17.42 14.4912 17.8467 15.1464 18.1373C15.8017 18.4278 16.5113 18.5756 17.228 18.5708C18.478 18.581 19.6873 18.1264 20.621 17.2953C21.5547 16.4641 22.1462 15.3157 22.2809 14.0729C22.4687 12.4165 21.871 10.8541 20.6057 9.69461C20.1054 9.23527 19.1781 8.73835 18.4917 8.55905C17.976 8.42415 16.8985 8.37121 16.4169 8.45659ZM18.6249 23.1421C19.5179 23.2445 20.1754 23.3897 20.9848 23.6629C23.1893 24.4091 25.0677 25.8623 26.0137 27.5562L26.2681 28.0088L26.0683 28.1727C25.6158 28.5415 24.289 29.3185 23.5069 29.672C21.4066 30.6214 19.5384 31.0244 17.228 31.0244C14.9176 31.0244 13.0495 30.6214 10.9491 29.672C10.167 29.3185 8.84023 28.5415 8.38771 28.1727L8.18792 28.0088L8.44236 27.5562C9.73161 25.2493 12.6226 23.5092 15.7714 23.1421C16.7209 23.0618 17.6754 23.0618 18.6249 23.1421Z" fill="black"></path>
                                                    </svg>
                                                </div>
                                                <div class="customer-info">
                                                    <span class="d-block">
                                                        Admin
                                                    </span>
                                                    <span class="d-block fst-italic fw-bold">
                                                        "<?= $v->testiBalasan ?>"
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- <span class="d-block fst-italic small <?= $v->testimoniBalasan ? null : 'd-none' ?>">
                                    [Admin] "<?= $v->testimoniBalasan ?>"
                                </span> -->
                                </div>
                        <?php }
                        endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /Transaksi -->