<!-- Profil -->
<div class="container mt-5 pt-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-6 mb-5">
            <h3 class="fw-bold text-center">Profil</h3>
            <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                <?= form_open_multipart(base_url('Customer/profil_update')) ?>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label" for="customerNama">Nama Lengkap</label>
                        <input class="form-control <?= form_error('customerNama') ? 'is-invalid' : null; ?>" id="customerNama" name="customerNama" type="text" value="<?= $profil->customerNama ?>" readonly>
                        <div class="invalid-feedback">
                            <?= form_error('customerNama') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerNoHp">Nomor Handphone</label>
                        <input class="form-control <?= form_error('customerNoHp') ? 'is-invalid' : null; ?>" id="customerNoHp" name="customerNoHp" type="number" value="<?= $profil->customerNoHp ?>" readonly>
                        <div class="invalid-feedback">
                            <?= form_error('customerNoHp') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerEmail">Email</label>
                        <input class="form-control <?= form_error('customerEmail') ? 'is-invalid' : null; ?>" id="customerEmail" name="customerEmail" type="email" value="<?= $profil->customerEmail ?>" readonly>
                        <div class="invalid-feedback">
                            <?= form_error('customerEmail') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerTglLahir">Tanggal Lahir</label>
                        <input class="form-control <?= form_error('customerTglLahir') ? 'is-invalid' : null; ?>" id="customerTglLahir" name="customerTglLahir" type="date" value="<?= $profil->customerTglLahir ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerTglLahir') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <div class="form-check <?= form_error('customerJenisKelamin') ? 'is-invalid' : null; ?>">
                            <input class="form-check-input" type="radio" name="customerJenisKelamin" id="customerJenisKelamin1" value="Laki-laki" <?= $profil->customerJenisKelamin == 'Laki-laki' ? 'checked' : null ?>>
                            <label class="form-check-label" for="customerJenisKelamin1">
                                Laki-laki
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="customerJenisKelamin" id="customerJenisKelamin2" value="Perempuan" <?= $profil->customerJenisKelamin == 'Perempuan' ? 'checked' : null ?>>
                            <label class="form-check-label" for="customerJenisKelamin2">
                                Perempuan
                            </label>
                        </div>
                        <div class="invalid-feedback">
                            <?= form_error('customerJenisKelamin') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerFoto">Foto</label>
                        <?php if ($profil->customerFoto != 'default.png') { ?>
                            <img class="img img-fluid w-25 d-block mb-2" src="<?= base_url('_uploads/profil/' . $profil->customerFoto) ?>">
                        <?php } else { ?>
                            <svg class="w-25 d-block mb-2" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.7936 0.603279C13.6813 0.804777 11.9191 1.30682 9.9536 2.2682C6.74248 3.83913 4.14711 6.4363 2.57841 9.64851C1.74168 11.3493 1.31136 12.7137 1.03302 14.5511C0.836648 15.8454 0.836648 17.968 1.03302 19.2794C1.36461 21.5394 2.16552 23.705 3.38424 25.6369C4.60296 27.5688 6.2125 29.2242 8.10937 30.4968C10.7968 32.3247 13.978 33.2891 17.228 33.2614C21.55 33.2614 25.5185 31.6596 28.5905 28.673C30.5525 26.7673 31.8384 24.7404 32.728 22.1517C33.3308 20.3979 33.5648 18.9106 33.5614 16.8546C33.5579 14.1447 33.0747 12.0819 31.8776 9.64851C30.5918 7.03415 28.6981 4.8911 26.2101 3.22959C25.4212 2.70194 23.7767 1.87716 22.8119 1.52368C20.6603 0.734765 18.0374 0.389827 15.7936 0.603279ZM18.9356 2.67803C23.1688 3.19031 26.8982 5.50755 29.2684 9.10207C30.404 10.8251 31.2339 13.1201 31.5003 15.2819C31.6044 16.1221 31.5874 17.886 31.4678 18.7996C31.1435 21.2566 30.1868 23.5873 28.6912 25.5635C28.0355 26.4309 27.8067 26.6768 27.7657 26.5624C27.6359 26.2158 26.7941 25.1178 26.2118 24.5389C24.7603 23.0908 22.7505 21.9826 20.5835 21.4345C18.63 20.9392 15.8961 20.9273 13.9836 21.4037C11.68 21.9792 9.6855 23.0755 8.17426 24.6004C7.65344 25.1263 6.81159 26.2397 6.69035 26.5624C6.64936 26.6751 6.42908 26.4395 5.7614 25.5635C4.61447 24.0596 3.78541 22.3381 3.32464 20.5038C3.02006 19.339 2.8719 18.1388 2.88408 16.9349C2.88408 15.2324 3.08387 13.9944 3.61493 12.3892C4.47097 9.81922 6.03683 7.54393 8.13152 5.82636C10.2262 4.10879 12.7642 3.01906 15.4521 2.68315C16.4118 2.56362 17.9708 2.56191 18.9356 2.67803ZM16.4169 8.45659C14.1424 8.86301 12.4348 10.6611 12.1786 12.9203C12.0935 13.632 12.1609 14.3537 12.3763 15.0374C12.5916 15.721 12.95 16.351 13.4276 16.8855C13.9053 17.42 14.4912 17.8467 15.1464 18.1373C15.8017 18.4278 16.5113 18.5756 17.228 18.5708C18.478 18.581 19.6873 18.1264 20.621 17.2953C21.5547 16.4641 22.1462 15.3157 22.2809 14.0729C22.4687 12.4165 21.871 10.8541 20.6057 9.69461C20.1054 9.23527 19.1781 8.73835 18.4917 8.55905C17.976 8.42415 16.8985 8.37121 16.4169 8.45659ZM18.6249 23.1421C19.5179 23.2445 20.1754 23.3897 20.9848 23.6629C23.1893 24.4091 25.0677 25.8623 26.0137 27.5562L26.2681 28.0088L26.0683 28.1727C25.6158 28.5415 24.289 29.3185 23.5069 29.672C21.4066 30.6214 19.5384 31.0244 17.228 31.0244C14.9176 31.0244 13.0495 30.6214 10.9491 29.672C10.167 29.3185 8.84023 28.5415 8.38771 28.1727L8.18792 28.0088L8.44236 27.5562C9.73161 25.2493 12.6226 23.5092 15.7714 23.1421C16.7209 23.0618 17.6754 23.0618 18.6249 23.1421Z" fill="black" />
                            </svg>
                        <?php } ?>
                        <input class="form-control <?= form_error('customerFoto') ? 'is-invalid' : null; ?>" id="customerFoto" name="customerFoto" type="file" accept="image/png, image/jpg, image/jpeg">
                        <div class="invalid-feedback">
                            <?= form_error('customerFoto') ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary w-100 waitme mb-3">Simpan</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
        <div class="col-md-6 mb-5">
            <h3 class="fw-bold text-center">Alamat Pengiriman</h3>
            <div class="card shadow p-3 mb-5 bg-body rounded border-0">
                <?= form_open(base_url('Customer/alamat_update')) ?>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatPenerima">Nama Penerima</label>
                        <input class="form-control <?= form_error('customerAlamatPenerima') ? 'is-invalid' : null; ?>" id="customerAlamatPenerima" name="customerAlamatPenerima" type="text" value="<?= $profil->customerAlamatPenerima ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatPenerima') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatNoHp">Nomor Handphone</label>
                        <input class="form-control <?= form_error('customerAlamatNoHp') ? 'is-invalid' : null; ?>" id="customerAlamatNoHp" name="customerAlamatNoHp" type="number" value="<?= $profil->customerAlamatNoHp ?>">
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatNoHp') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatProvinsiId">Provinsi</label>
                        <select class="form-select <?= form_error('customerAlamatProvinsiId') ? 'is-invalid' : null; ?>" id="customerAlamatProvinsiId" name="customerAlamatProvinsiId">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($provinces as $key => $v) { ?>
                                <option value="<?= $v->province_id ?>" <?= $profil->customerAlamatProvinsiId == $v->province_id ? 'selected' : null ?>><?= $v->province ?></option>
                            <?php } ?>
                        </select>
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatProvinsiId') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatKotkabId">Kota / Kabupaten</label>
                        <select class="form-select <?= form_error('customerAlamatKotkabId') ? 'is-invalid' : null; ?>" id="customerAlamatKotkabId" name="customerAlamatKotkabId">
                            <option value="">-- Pilih --</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatKotkabId') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatKecamatanId">Kecamatan</label>
                        <select class="form-select <?= form_error('customerAlamatKecamatanId') ? 'is-invalid' : null; ?>" id="customerAlamatKecamatanId" name="customerAlamatKecamatanId">
                            <option value="">-- Pilih --</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatKecamatanId') ?>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label" for="customerAlamatLengkap">Alamat Lengkap</label>
                        <textarea class="form-control <?= form_error('customerAlamatLengkap') ? 'is-invalid' : null; ?>" id="customerAlamatLengkap" name="customerAlamatLengkap" rows="7"><?= $profil->customerAlamatLengkap; ?></textarea>
                        <div class="invalid-feedback">
                            <?= form_error('customerAlamatLengkap') ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary w-100 waitme mb-3">Simpan</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>
<!-- /Profil -->

<script>
    $(document).ready(function() {
        var provId = $("#customerAlamatProvinsiId").val();
        if (provId != '') {
            $("#customerAlamatProvinsiId").trigger('change');
            $("#customerAlamatKotkabId").val("<?= $profil->customerAlamatKotkabId ?>").change();
            $("#customerAlamatKecamatanId").val("<?= $profil->customerAlamatKecamatanId ?>").change();
        }
    });

    $("#customerAlamatProvinsiId").change(function() {
        var cities = JSON.parse(JSON.stringify(<?= json_encode($cities) ?>));
        $("#customerAlamatKotkabId").find('option').remove();
        $("#customerAlamatKotkabId").append('<option>-- Pilih --</option>');

        let prov = this.value;
        const found_city = cities.filter(v => v.province_id === prov);

        $.each(found_city, function(index, value) {
            $("#customerAlamatKotkabId").append(`
                <option value="` + value.city_id + `">` + value.type + ` ` + value.city_name + `</option>
            `);
        });
    });

    $("#customerAlamatKotkabId").change(function() {
        var subdistricts = JSON.parse(JSON.stringify(<?= json_encode($subdistricts) ?>));
        $("#customerAlamatKecamatanId").find('option').remove();
        $("#customerAlamatKecamatanId").append('<option>-- Pilih --</option>');

        let city = this.value;
        const found_subdistrict = subdistricts.filter(v => v.city_id === city);

        $.each(found_subdistrict, function(index, value) {
            $("#customerAlamatKecamatanId").append(`
                <option value="` + value.subdistrict_id + `">` + value.subdistrict_name + `</option>
            `);
        });
    });
</script>