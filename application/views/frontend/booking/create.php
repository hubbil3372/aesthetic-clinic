<div class="container mt-5 min-vh-100">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9 mb-3">
            <h3 class="fw-bold text-center">Booking Treatment</h3>
        </div>
        <div class="col-md-9">
            <div class="card shadow px-3 py-2 mb-3 bg-body rounded border-0">
                <div class="card-body">
                    <?= form_open(null, ['method' => 'POST']) ?>
                    <div class="card card-body mb-3">
                        <div class="row">
                            <div class="col-2">
                                <img class="img img-fluid" style="width: 100%;" src="<?= base_url("_uploads/treatment/{$treatment->treatmentFoto}") ?>" alt="">
                            </div>
                            <div class="col-10 d-flex">
                                <div class="align-self-center">
                                    <h4 class="fs-4"><?= $treatment->treatmentNama ?></h4>
                                    <h4 class="fs-5 d-inline-block fw-bold">Rp<?= $treatment->treatmentHarga - $treatment->treatmentDiskon ?></h4>
                                    <div class="">
                                        <small class="d-inline-block text-decoration-line-through">Rp<?= $treatment->treatmentHarga ?></small>
                                        <small class="fw-bold d-inline-block text-danger"><?= round($treatment->treatmentDiskon / $treatment->treatmentHarga * 100) . '%' ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" name="treatmentId" id="treatmentId" value="<?= $treatment->treatmentId ?>">
                            <label for="bookingJdId" class="form-label">Pilih Dokter</label>
                            <select name="bookingJdId" id="bookingJdId" class="form-control select2 <?= form_error('bookingJdId') ? 'is-invalid' : null ?>" data-placeholder="Pilih">
                                <?php if (!$jadwal) : ?>
                                    <option value="">Jadwal Belum Tersedia</option>
                                <?php endif;
                                foreach ($jadwal as $key => $value) { ?>
                                    <option value="<?= $value->jdId ?>" <?= $value->jdId == set_value('bookingJdId') ? 'selected' : null ?>><?= $value->dokterNama . ' - ' . substr($value->jdJamAwal, 0, 5) . ' s/d ' . substr($value->jdJamAkhir, 0, 5) ?> </option>
                                <?php } ?>
                            </select>
                            <?= form_error('bookingJdId', '<div class="invalid-feedback">', '</div>') ?>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bookingTgl" class="form-label">Tanggal</label>
                                        <input type="date" class="form-control <?= form_error('bookingTgl') ? 'is-invalid' : null ?>" name="bookingTgl" id="bookingTgl" value="<?= set_value('bookingTgl') ?>">
                                        <?= form_error('bookingTgl', '<div class="invalid-feedback">', '</div>') ?>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bookingWaktu" class="form-label">Pilih Waktu Treatment</label>
                                        <select class="form-control <?= form_error('bookingWaktu') ? 'is-invalid' : null ?>" name="bookingWaktu" id="bookingWaktu">
                                            <option value="30">30 Menit</option>
                                            <option value="60" <?= set_value('bookingWaktu') == '60' ? 'selected' : null ?>>1 Jam</option>
                                            <option value="90" <?= set_value('bookingWaktu') == '90' ? 'selected' : null ?>>1 Jam 30 Menit</option>
                                            <option value="120" <?= set_value('bookingWaktu') == '120' ? 'selected' : null ?>>2 Jam</option>
                                        </select>
                                        <?= form_error('bookingWaktu', '<div class="invalid-feedback">', '</div>') ?>
                                    </div>
                                </div> -->

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="bookingWaktu" class="form-label">Pilih Waktu Treatment</label>
                                        <select class="form-control <?= form_error('bookingWaktu') ? 'is-invalid' : null ?>" name="bookingWaktu" id="bookingWaktu" data-id="<?= $this->input->post('bookingWaktu') ?>">
                                            <option value="30">30 Menit</option>
                                            <option value="60" <?= set_value('bookingWaktu') == '60' ? 'selected' : null ?>>1 Jam</option>
                                            <option value="90" <?= set_value('bookingWaktu') == '90' ? 'selected' : null ?>>1 Jam 30 Menit</option>
                                            <option value="120" <?= set_value('bookingWaktu') == '120' ? 'selected' : null ?>>2 Jam</option>
                                        </select>
                                        <?= form_error('bookingWaktu', '<div class="invalid-feedback">', '</div>') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="bookingVoucher" class="form-label">Voucher <small>(opsional)</small></label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control bookingVoucher" aria-label="Voucher" aria-describedby="bookingVoucher" name="bookingVoucher">
                                <span class="input-group-text" id="bookingVoucher" style="cursor: pointer" onclick="cek_voucher()">Pakai</span>
                                <div id="invalid" class="invalid-feedback d-none">
                                    Voucher tidak ditemukan atau sudah tidak aktif!
                                </div>

                                <div id="valid" class="valid-feedback d-none">
                                    Voucher berhasil dipakai!
                                </div>
                                <span id="potongan_harga" class="invisible">0</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="bookingDp" class="form-label">Dana Pertama <small class="text-primary">(Dana dapat ditambah)</small> </label>
                                <input type="text" class="form-control <?= form_error('bookingDp') ? 'is-invalid' : null ?>" name="bookingDp" id="bookingDp" value="<?= $this->input->post('bookingDp') != "" ? $this->input->post('bookingDp') : $bookingDp ?>">
                                <?= form_error('bookingDp', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="bookingKeluhan" class="form-label">Keluhan <small>(opsional)</small></label>
                                <textarea name="bookingKeluhan" id="bookingKeluhan" rows="5" class="form-control <?= form_error('bookingKeluhan') ? 'is-invalid' : null ?>"><?= set_value('bookingKeluhan') ?></textarea>
                                <?= form_error('bookingKeluhan', '<div class="invalid-feedback">', '</div>') ?>
                            </div>
                        </div>

                        <div class="co-md-12 border-bottom pb-2">
                            <b class="mb-3 fs-5 text-dark">Total Tagihan</b>
                            <b class="fs-5 float-end">
                                <span id="grand_total"><?= $treatment->treatmentHarga - $treatment->treatmentDiskon ?></span>
                            </b>
                        </div>
                        <div class="co-md-12 mb-3">
                            <b class="mb-3 fs-5 text-primary">Tagihan Awal (30%)</b>
                            <b class="fs-5 text-primary float-end">
                                <span id="total_tagihan"><?= $bookingDp ?></span>
                            </b>
                        </div>

                        <!-- <input type="text" name="bookingHarga" id="bookingHarga" value="<?= $bookingDp ?>"> -->
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-6 d-grid">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-edit"></i> Buat Pesanan</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        let potongan_harga = $("#potongan_harga");
        // console.log(potongan_harga.text());
        let dp = document.getElementById("bookingDp");
        let grandTotal = document.getElementById("grand_total");
        let totalTagihan = document.getElementById("total_tagihan");
        dp.value = formatRupiah(dp.value, "Rp. ");
        totalTagihan.innerHTML = dp.value;
        grandTotal.innerHTML = formatRupiah(grandTotal.textContent, "Rp. ")
        // console.log(grandTotal.textContent);

        dp.addEventListener("keyup", function(e) {
            e.target.value = formatRupiah(e.target.value, "Rp. ");
            totalTagihan.innerHTML = e.target.value
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


        let jadwalDokter = $("#bookingJdId");
        let pilihWaktu = $("#bookingWaktu");
        let selectedWaktu;

        pilihWaktu.html("<option value=''>Pilih Jadwal</option>")
        $.ajax({
            type: "GET",
            url: "<?= base_url("booking/jadwal_waktu/") ?>" + jadwalDokter.val() + "/true",
            dataType: "JSON",
            success: function(result) {
                $.each(result, function(i, value) {
                    if (pilihWaktu.data('id') != null) {
                        if (pilihWaktu.data('id') == value.from) {
                            selectedWaktu = 'selected'
                        } else {
                            selectedWaktu = ''
                        }
                    }
                    pilihWaktu.append('<option value="' + value.from + '"' + selectedWaktu + '>' + value.from + ' - ' + value.to + '</option>');
                });
            }
        })

        jadwalDokter.change(function() {
            console.log(jadwalDokter.val());
            pilihWaktu.html("<option value=''>Pilih Jadwal</option>")
            $.ajax({
                type: "GET",
                url: "<?= base_url("booking/jadwal_waktu/") ?>" + jadwalDokter.val() + "/true",
                dataType: "JSON",
                success: function(result) {
                    $.each(result, function(i, value) {
                        if (pilihWaktu.data('id') != null) {
                            if (pilihWaktu.data('id') == value.from) {
                                selectedWaktu = 'selected'
                            } else {
                                selectedWaktu = ''
                            }
                        }
                        pilihWaktu.append('<option value="' + value.from + '"' + selectedWaktu + '>' + value.from + ' - ' + value.to + '</option>');
                    });
                }
            })
        })
    })


    $('.select2').select2({
        theme: "bootstrap-5",
        placeholder: $(this).data('placeholder'),
    });

    function cek_voucher() {
        var kode = $('.bookingVoucher').val();
        $.ajax({
            type: "GET",
            dataType: "json",
            url: '<?= base_url('booking/voucher/') ?>' + kode,
            success: function(response) {
                if (response.status == false) {
                    $('#invalid').removeClass('d-none');
                    $('#valid').addClass('d-none');
                    $('.bookingVoucher').addClass('is-invalid');
                    $('.bookingVoucher').removeClass('is-valid');
                    potongan_harga.text = 0;
                    $('input[name="ongkoskirim"]').trigger("click");
                } else {
                    $('#valid').removeClass('d-none');
                    $('#invalid').addClass('d-none');
                    $('.bookingVoucher').addClass('is-valid');
                    $('.bookingVoucher').removeClass('is-invalid');
                    $('#valid').html('Voucher berhasil dipakai! Anda mendapatkan potongan harga Rp' + response.data.voucherPotongan);
                    potongan_harga.textContent = parseInt(response.data.voucherPotongan);
                    // console.log(parseInt(response.data.voucherPotongan));
                    $('input[name="ongkoskirim"]').trigger("click");
                }
            }
        });
    };
</script>