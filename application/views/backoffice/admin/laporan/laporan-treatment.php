<!DOCTYPE html>
<html lang="en">
<!-- For RTL verison -->
<!-- <html lang="en" dir="rtl"> -->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title><?= $title; ?> | <?= SITE_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" content="AdminLTE 4 | Dashboard">
    <meta name="author" content="ColorlibHQ">
    <meta name="description" content="Volt Pro is a Premium Bootstrap 5 Admin Dashboard featuring over 800 components, 10+ plugins and 20 example pages using Vanilla JS.">
    <meta name="keywords" content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard" />
    <!-- By adding ./css/dark/adminlte-dark-addon.css then the page supports both dark color schemes, and the page author prefers / default is light. -->
    <meta name="color-scheme" content="light dark">
    <!-- By adding ./css/dark/adminlte-dark-addon.css then the page supports both dark color schemes, and the page author prefers / default is dark. -->
    <!-- <meta name="color-scheme" content="dark light"> -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/vendor/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/css/adminlte.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/adminlte/vendor/overlayscrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- WaitMe -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/waitme/css/waitMe.min.css">
    <!-- Style -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/css/style.css">
    <!-- Datatables BS 5 -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <!-- Dropify -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css">
    <!-- Summernote -->
    <link rel="stylesheet" href="<?= base_url(); ?>_assets/vendor/summernote/css/summernote.min.css">
    <!-- Jquery -->
    <script src="<?= base_url(); ?>_assets/vendor/jquery/js/jquery-3.6.0.min.js"></script>

    <style>
        @page {
            size: landscape;
        }
    </style>
</head>

<body class="layout-fixed">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <h4 class="fw-bold"><?= $title ?></h4>
                        <h6 class="fw-bold"><?= date('d M Y', strtotime($period['start'])) . ' - ' . date('d M Y', strtotime($period['end'])) ?></h6>
                    </center>
                    <br>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Booking</th>
                                <th>Pelanggan</th>
                                <th>Status Bayar</th>
                                <th>Status Booking</th>
                                <th>Total Tagihan</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($laporan as $key => $v) { ?>
                                <tr>
                                    <td><?= ++$key ?></td>
                                    <th><?= $v->bookingKode ?></th>
                                    <td><?= $v->customerNama ?></td>
                                    <td><?= $v->bookingStatusBayar ?></td>
                                    <td><?= $v->bookingStatus ?></td>
                                    <th><?= 'Rp' . $v->bookingHarga ?></th>
                                    <td><?= date('d/m/Y', strtotime($v->bookingDibuatPada)) ?></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td colspan="6">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <span class="d-block fs-5">
                                                    <?= $v->bdTreatmentNama ?>
                                                </span>
                                                <span class="d-block">
                                                    <?= $v->bdTreatmentDeskripsi ?>
                                                </span>
                                                <span class="d-block">
                                                    <?= $v->dokterNama ?>
                                                </span>
                                            </div>
                                            <div class="col-6">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <small class="d-block text-secondary">Mulai : <?= date('H:i', strtotime($v->bookingJamAwal)) ?></small>
                                                    </div>
                                                    <div class="col-3">
                                                        <small class="d-block text-secondary">Selesai : <?= date('H:i', strtotime($v->bookingJamAkhir)) ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-9">
                                                <span class="d-block">
                                                    Harga Treatment
                                                </span>
                                            </div>
                                            <div class="col-3 text-end">
                                                <span class="d-block fw-bold">
                                                    Rp<?= $v->bdTreatmentHarga ?>
                                                </span>
                                            </div>
                                            <div class="col-9">
                                                <span class="d-block">
                                                    Diskon
                                                </span>
                                            </div>
                                            <div class="col-3 text-end">
                                                <span class="d-block fw-bold">
                                                    Rp<?= $v->bdTreatmentDiskon ?>
                                                </span>
                                            </div>
                                            <div class="col-9">
                                                <span class="d-block">
                                                    Potongan Voucher
                                                </span>
                                            </div>
                                            <div class="col-3 text-end">
                                                <span class="d-block fw-bold">
                                                    -Rp<?= $v->bookingVoucherPotongan ?>
                                                </span>
                                            </div>
                                            <div class="col-9">
                                                <span class="d-block">
                                                    Harga Net
                                                </span>
                                            </div>
                                            <div class="col-3 text-end">
                                                <span class="d-block fw-bold">
                                                    Rp<?= $v->bookingHarga ?>
                                                </span>
                                            </div>
                                            <div class="col-9">
                                                <span class="d-block">
                                                    Dana Booking
                                                </span>
                                            </div>
                                            <div class="col-3 text-end">
                                                <span class="d-block fw-bold">
                                                    Rp<?= $v->bookingDp ?>
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--REQUIRED SCRIPTS-->
    <!--overlayScrollbars-->
    <script script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/overlayscrollbars/js/OverlayScrollbars.min.js"></script>
    <!-- Bootstrap 5 -->
    <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>_assets/vendor/adminlte/js/adminlte.js"></script>
    <!-- sweetalert JS -->
    <script src="<?= base_url() ?>_assets/vendor/sweetalert/js/sweetalert2.all.min.js"></script>
    <!-- ChartJS -->
    <script src="<?= base_url(); ?>_assets/vendor/adminlte/vendor/chart.js/dist/chart.js"></script>
    <!-- Datatables -->
    <script src="<?= base_url(); ?>_assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>_assets/vendor/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
    <!-- WaitMe -->
    <script src="<?= base_url(); ?>_assets/vendor/waitme/js/waitMe.min.js"></script>
    <!-- Dropify -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>
    <!-- Summernote -->
    <script src="<?= base_url(); ?>_assets/vendor/summernote/js/summernote.min.js"></script>
    <!-- Script -->
    <script type="text/javascript">
        window.print();
    </script>
</body>

</html>