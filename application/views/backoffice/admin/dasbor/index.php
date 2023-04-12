<div class="container-fluid">
  <!-- Small boxes (Stat box) -->
  <section class="row">
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3><?= $tot_checkout ?></h3>

          <p>Transaksi</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-stats-bars"></i>
        </div>
        <a href="<?= base_url('backoffice/transaksi') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3><?= $tot_customer ?></h3>

          <p>Customer</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-person"></i>
        </div>
        <a href="<?= base_url('backoffice/customer') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3><?= $tot_voucher ?></h3>

          <p>Voucher</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-pie-graph"></i>
        </div>
        <a href="<?= base_url('backoffice/voucher') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-6">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3><?= $tot_produk ?></h3>

          <p>Produk</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-bag"></i>
        </div>
        <a href="<?= base_url('backoffice/produk') ?>" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class=" col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3><?= $tot_produk ?></h3>

          <p>Dokter</p>
        </div>
        <div class="icon">
          <i class="fas fa-user-md"></i>
        </div>
      </div>
    </div>
    <div class=" col-6">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3><?= $tot_produk ?></h3>

          <p>Treatment</p>
        </div>
        <div class="icon">
          <i class="inner-icon ion ion-bag"></i>
        </div>
      </div>
    </div>
    <!-- ./col -->
    <!-- <div class="col-md-12">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center w-100">
            <h3 class="card-title">Cetak Laporan</h3>
          </div>
          <div class="card-body">
            <?= form_open() ?>
            <div class="row">
              <div class="col-md-5">
                <label for="start">Tanggal Awal</label>
                <input class="form-control <?= form_error('start') ? 'is-invalid' : null; ?>" type="date" id="start" name="start" value="<?= set_value('start'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('start') ?>
                </div>
              </div>
              <div class="col-md-5">
                <label for="end">Tanggal Akhir</label>
                <input class="form-control <?= form_error('end') ? 'is-invalid' : null; ?>" type="date" id="end" name="end" value="<?= set_value('end'); ?>">
                <div class="invalid-feedback">
                  <?= form_error('end') ?>
                </div>
              </div>
              <div class="col-md-2">
                <label for="end">.</label>
                <button type="submit" class="btn btn-primary d-block w-100 waitme">Cetak</button>
              </div>
            </div>
            <?= form_close() ?>
          </div>
        </div>
      </div>
    </div> -->
  </section>
  <!-- /.row -->
</div><!-- /.container-fluid -->