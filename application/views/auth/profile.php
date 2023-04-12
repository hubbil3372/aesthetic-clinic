<div class="content">
  <div class="container-fluid">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="row">
        <div class="col-md-8">
          <div class="card card-info card-outline">
            <div class="card-header card-header-primary">
              <h2 class="card-title"><?= $title; ?></h4>
            </div>
            <div class="card-body mt-3">
              <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-4">
                    <label class="form-label" for="pengEmail">Alamat Email</label>
                    <input class="form-control <?= form_error('pengEmail') ? 'is-invalid' : null; ?>" id="pengEmail" type="email" name="pengEmail" value="<?= $this->input->post('pengEmail') ?? $user->pengEmail; ?>" disabled>
                    <?= form_error('pengEmail', '<small class="text-danger">', '</small>'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-4">
                    <label class="form-label" for="pengNama">Nama Lengkap</label>
                    <input class="form-control <?= form_error('pengNama') ? 'is-invalid' : null; ?>" id="pengNama" type="text" name="pengNama" value="<?= $this->input->post('pengNama') ?? $user->pengNama; ?>">
                    <?= form_error('pengNama', '<small class="text-danger">', '</small>'); ?>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="mb-4">
                    <label class="form-label" for="pengTlp">Phone</label>
                    <input class="form-control" type="tel" name="pengTlp" value="<?= $this->input->post('pengTlp') ?? $user->pengTlp; ?>">
                  </div>
                </div>
              </div>
              <button class="btn btn-info text-white waitme pull-right waitme" type="submit">Ubah Data Diri</button>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card card-profile card-info card-outline">
            <div class="card-body">
              <div class="profile d-flex flex-column justify-content-center ">
                <?php $image = $user->pengFoto == 'default.jpg' ? "https://avatar.oxro.io/avatar.svg?name={$user->pengNama}&background=157347&caps=3&bold=true" : base_url("_assets/images/profile/{$user->pengFoto}"); ?>
                <label class="text-center mb-3" for="image">
                  <img class="user-image img-circle shadow mx-auto cursor wh-image-150px" src="<?= $image; ?>" alt="User Image">
                  <input type="file" name="image" id="image">
                </label>

                <h6 class="h3 card-category text-gray text-center"><?= $user->pengNama; ?></h6>
                <h4 class="h4 card-title mb-3 text-center"><?= $this->ion_auth->get_users_groups($user->pengId)->row()->grupNama; ?></h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>