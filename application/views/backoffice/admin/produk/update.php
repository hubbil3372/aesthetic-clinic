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
                  <label class="form-label" for="produkNama">Produk</label>
                  <input class="form-control <?= form_error('produkNama') ? 'is-invalid' : null; ?>" id="produkNama" name="produkNama" type="text" value="<?= $this->input->post('produkNama') ?? $produk->produkNama; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('produkNama') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkKategoriId">Kategori</label>
                  <select class="form-select <?= form_error('produkKategoriId') ? 'is-invalid' : null; ?>" id="produkKategoriId" name="produkKategoriId">
                    <option value="">-- Pilih --</option>
                    <?php foreach ($kategori as $key => $v) { ?>
                      <option value="<?= $v->kategoriId ?>" <?= $produk->produkKategoriId == $v->kategoriId ? 'selected' : null ?>><?= $v->kategoriNama ?></option>
                    <?php } ?>
                  </select>
                  <div class="invalid-feedback">
                    <?= form_error('produkKategoriId') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkDeskripsi">Deskripsi</label>
                  <textarea class="form-control <?= form_error('produkDeskripsi') ? 'is-invalid' : null; ?>" id="produkDeskripsi" name="produkDeskripsi" rows="7"><?= $this->input->post('produkDeskripsi') ?? $produk->produkDeskripsi; ?></textarea>
                  <div class="invalid-feedback">
                    <?= form_error('produkDeskripsi') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkHarga">Harga (Rp)</label>
                  <input class="form-control <?= form_error('produkHarga') ? 'is-invalid' : null; ?>" id="produkHarga" name="produkHarga" type="number" value="<?= $this->input->post('produkHarga') ?? $produk->produkHarga; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('produkHarga') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkDiskon">Diskon (Rp)</label>
                  <input class="form-control <?= form_error('produkDiskon') ? 'is-invalid' : null; ?>" id="produkDiskon" name="produkDiskon" type="number" value="<?= $this->input->post('produkDiskon') ?? $produk->produkDiskon; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('produkDiskon') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkStok">Stok</label>
                  <input class="form-control <?= form_error('produkStok') ? 'is-invalid' : null; ?>" id="produkStok" name="produkStok" type="number" value="<?= $this->input->post('produkStok') ?? $produk->produkStok; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('produkStok') ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="mb-4">
                  <label class="form-label" for="produkBerat">Berat (gram)</label>
                  <input class="form-control <?= form_error('produkBerat') ? 'is-invalid' : null; ?>" id="produkBerat" name="produkBerat" type="number" value="<?= $this->input->post('produkBerat') ?? $produk->produkBerat; ?>">
                  <div class="invalid-feedback">
                    <?= form_error('produkBerat') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkGambar1">Gambar 1</label>
                  <img class="img img-fluid d-block w-25 mb-3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar1) ?>">
                  <input class="form-control <?= form_error('produkGambar1') ? 'is-invalid' : null; ?>" id="produkGambar1" name="produkGambar1" type="file" accept="image/png, image/jpg, image/jpeg">
                  <div class="invalid-feedback">
                    <?= form_error('produkGambar1') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkGambar2">Gambar 2</label>
                  <?php if ($produk->produkGambar2) ?>
                  <img class="img img-fluid d-block w-25 mb-3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar2) ?>">
                  <?php  ?>
                  <input class="form-control <?= form_error('produkGambar2') ? 'is-invalid' : null; ?>" id="produkGambar2" name="produkGambar2" type="file" accept="image/png, image/jpg, image/jpeg">
                  <div class="invalid-feedback">
                    <?= form_error('produkGambar2') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label" for="produkGambar3">Gambar 3</label>
                  <?php if ($produk->produkGambar3) ?>
                  <img class="img img-fluid d-block w-25 mb-3" src="<?= base_url('_uploads/produk/' . $produk->produkGambar3) ?>">
                  <?php  ?>
                  <input class="form-control <?= form_error('produkGambar3') ? 'is-invalid' : null; ?>" id="produkGambar3" name="produkGambar3" type="file" accept="image/png, image/jpg, image/jpeg">
                  <div class="invalid-feedback">
                    <?= form_error('produkGambar3') ?>
                  </div>
                </div>
                <div class="mb-4">
                  <label class="form-label">Status</label>
                  <div class="form-check <?= form_error('produkStatus') ? 'is-invalid' : null; ?>">
                    <input class="form-check-input" type="radio" name="produkStatus" id="produkStatus1" value="1" <?= $produk->produkStatus == 1 ? 'checked' : null ?>>
                    <label class="form-check-label" for="produkStatus1">
                      Aktif
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="produkStatus" id="produkStatus2" value="0" <?= $produk->produkStatus == 0 ? 'checked' : null ?>>
                    <label class="form-check-label" for="produkStatus2">
                      Tidak Aktif
                    </label>
                  </div>
                  <div class="invalid-feedback">
                    <?= form_error('produkStatus') ?>
                  </div>
                </div>
              </div>
            </div>

            <button class="btn btn-success waitme" type="submit">Simpan</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>