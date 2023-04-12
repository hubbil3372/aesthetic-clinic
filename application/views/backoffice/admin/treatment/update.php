<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $title; ?></h3>
                </div>
                <div class="card-body">
                    <form class="align-self-center" action="" method="post" enctype="multipart/form-data">

                        <!-- ------------------------------------------------ -->
                        <!-- CSRF TOKEN -->
                        <!-- ------------------------------------------------ -->
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentNama">Nama Treatment</label>
                                    <input class="form-control <?= form_error('treatmentNama') ? 'is-invalid' : null; ?>" id="treatmentNama" name="treatmentNama" type="text" value="<?= $this->input->post('treatmentNama') ?? $treatment->treatmentNama; ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentNama') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentHarga">Harga</label>
                                    <input class="form-control <?= form_error('treatmentHarga') ? 'is-invalid' : null; ?>" id="treatmentHarga" name="treatmentHarga" type="text" pattern="[0-9]+" value="<?= $this->input->post('treatmentHarga') ?? $treatment->treatmentHarga; ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentHarga') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentDiskon">Diskon <small>Jika kosong nilai default 0</small></label>
                                    <input class="form-control <?= form_error('treatmentDiskon') ? 'is-invalid' : null; ?>" id="treatmentDiskon" name="treatmentDiskon" type="text" pattern="[0-9]+" value="<?= $this->input->post('treatmentDiskon') ?? $treatment->treatmentDiskon; ?>">
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentDiskon') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentStatus">Status Treatment</label>
                                    <select class="form-control <?= form_error('treatmentStatus') ? 'is-invalid' : null; ?>" id="treatmentStatus" name="treatmentStatus">
                                        <option value="0">Tidak Aktif</option>
                                        <option value="1" <?= $this->input->post('treatmentStatus' ?? $treatment->treatmentStatu) == '1' ? 'selected' : null; ?>>Aktif</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentStatus') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentFoto">Foto Treatment</label>
                                    <div class="<?= form_error('treatmentFoto') ? 'is-invalid' : null; ?>">
                                        <input class="form-control dropify <?= form_error('treatmentFoto') ? 'is-invalid' : null; ?>" data-height="80" id="treatmentFoto" name="treatmentFoto" type="file" <?= $treatment->treatmentFoto != null ? 'data-default-file="' . base_url('_uploads/treatment/') . $treatment->treatmentFoto . '"' : null ?>>
                                    </div>
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentFoto') ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label class="form-label" for="treatmentDeskripsi">Deskripsi Treatment</label>
                                    <textarea class="form-control <?= form_error('treatmentDeskripsi') ? 'is-invalid' : null; ?>" id="treatmentDeskripsi" name="treatmentDeskripsi" rows="5"><?= $this->input->post('treatmentDeskripsi') ?? $treatment->treatmentDeskripsi; ?></textarea>
                                    <div class="invalid-feedback">
                                        <?= form_error('treatmentDeskripsi') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button class="btn btn-info text-white px-5 waitme" type="submit">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.dropify').dropify({
            messages: {
                'default': 'Drag file anda disini',
                'replace': 'Drag file anda disini untuk ganti'
            },
            tpl: {
                wrap: '<div class="dropify-wrapper"></div>',
                loader: '<div class="dropify-loader"></div>',
                message: '<div class="dropify-message"><span class="file-icon" /> <p class="fs-5">{{ default }}</p></div>',
                preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
                filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner"></span></p>',
                clearButton: '<button type="button" class="dropify-clear">{{ remove }}</button>',
                errorLine: '<p class="dropify-error">{{ error }}</p>',
                errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
            }
        });
    })
</script>