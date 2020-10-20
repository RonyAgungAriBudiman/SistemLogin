<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?php echo $judul; ?></h1>
    <div class="row">
        <div class="col-lg-8">
            <?= $this->session->flashdata('message'); ?>
        </div>
    </div>

    <div class="card mb-3 col-lg-6">
        <div class="row no-gutters">
            <div class="col-md-4">
                <img src="<?php echo base_url(); ?>assets/img/profile/<?php echo $user['Image']; ?>" class="card-img">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $user['Nama']; ?></h5>
                    <p class="card-text"><?php echo $user['Email']; ?></p>
                    <p class="card-text"><small class="text-muted">Member sejak <?php echo date("d F Y", $user['DateCreated']) ?></small></p>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->